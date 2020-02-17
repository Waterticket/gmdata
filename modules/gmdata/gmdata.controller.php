<?PHP

class gmdataController extends gmdata
{
    function procGMdataInsertBoard() //ok
	{
        //권한 체크
        if(!$this->grant->create_gameboard)
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }
        //변수 불러오기
        $member_srl = Context::get('member_srl');
        $game_token = "CK_".bin2hex(openssl_random_pseudo_bytes(16));
		$game_secret = bin2hex(openssl_random_pseudo_bytes(5));
		
		$args = new stdClass;
		$args->regdate = zDate(date("YmdHis"),"YmdHis");
		$args->member_srl = $member_srl;
        $args->game_name = Context::get('game_title');
        $args->game_des = Context::get('game_des');
		$args->game_token = $game_token;
		$args->game_secret = $game_secret;
		$args->game_homepage = Context::get('game_homepage');
		$args->game_platform = Context::get('game_platform');
		$args->game_version = Context::get('game_version');
		$args->game_minversion = Context::get('game_minversion');
		$args->game_status = 0;
		$args->user_count = 0;
		$args->game_update_link = "";
		$args->game_update_name = "";
		$args->game_notice = Context::get('game_notice');
		$args->game_showversion = Context::get('game_showversion');
		$args->game_datas = Context::get('game_datas');
		
        //타이틀 유효성 체크 (생략)
        /*(if ( preg_match('/[^\x{1100}-\x{11FF}\x{3130}-\x{318F}\x{AC00}-\x{D7AF}0-9a-zA-Z]/u',$args->game_name) ) 
        { 
            return new BaseObject(-1, '게임타이틀에는 영어, 숫자, 한글만 입력 가능합니다.');
        } */
		
        //로그인 체크
        if(!$member_srl)
        {
            return new BaseObject(-1, "로그인하셔야 등록가능합니다.");
        }
        
        //보드 생성 가능여부 체크
        $oGMdataModel = getModel('gmdata');
        $config = $oGMdataModel->getConfig();
        $max_board=0;
        //프로일 경우
        if($oGMdataModel->checkIfPro($args->member_srl))
        {
            $max_board=$config->pro_max_board;
        }
        //프로가 아닐경우
        else
        {
            $max_board=$config->free_max_board;
        }
		
        if(!$oGMdataModel->checkIfavailable($args->member_srl, $max_board))
        {
            return new BaseObject(-1, "더 이상 생성할 수 없습니다.");
        }
        
        //게임타이틀 중복체크
        $oGMdataModel = getModel('gmdata');
        if($oGMdataModel->checkIfBoardExists($args->game_name))
        {
            return new BaseObject(-1, "이미 있는 게임타이틀입니다.");
        }
        $output = executeQuery('gmdata.insertBoard',$args);
        $this->setRedirectUrl(getNotEncodedUrl('','mid','gmdata','act','dispGMdataContent'));
	}
	
    function procGMdataUpdateBoard() //ok
	{
        //권한 체크
        if(!$this->grant->create_gameboard)
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }
        //변수 불러오기
        $member_srl = Context::get('member_srl');
		$args = new stdClass;	
		$args->regdate = zDate(date("YmdHis"),"YmdHis");
        $args->game_des = Context::get('game_des');
		$args->game_token = Context::get('game_token');
        $args->game_homepage = Context::get('game_homepage');
		$args->game_version = Context::get('game_version');
		$args->game_minversion = Context::get('game_minversion');
		$args->game_showversion = Context::get('game_showversion');
		$args->game_update_link = Context::get('game_update_link');
		$args->game_update_name = Context::get('game_update_name');
		$args->game_datas = Context::get('game_datas');
		$args->game_secret = Context::get('game_secret');
        //로그인 체크
        if(!$member_srl)
        {
            return new BaseObject(-1, "로그인하셔야 등록가능합니다.");
        }
        $output = executeQuery('gmdata.updateBoard',$args);
        $this->setRedirectUrl(getNotEncodedUrl('','mid','gmdata','act','dispGMdataContent'));
	}
	
    function procGMdatadeleteBoard() //ok
    {
        //권한 체크
        if(!$this->grant->create_gameboard)
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }
        $game_token = Context::get('game_token');
        if(!$game_token)
        {
            return new BaseObject(-1, 'Invalid Game Token');
        }
        $args = new stdClass;
        $args->game_token = $game_token;
        //점수 삭제
        executeQuery('gmdata.deleteScore',$args);
        //보드 삭제
        executeQuery('gmdata.deleteBoard',$args);
        
        $this->setRedirectUrl(getNotEncodedUrl('','mid','gmdata','act','dispGMdataContent'));
    }
	
    function procGMdataInsertPro() //ok
    {
        //권한 체크
        if(!$this->grant->create_gameboard)
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }
        //변수 로드
        $member_srl = Context::get('member_srl');
        $point = Context::get('point');
        $hash = Context::get('hash');
		$es_point = 1000;
        //변조 체크
        if($hash!=sha1($point.$member_srl.'cookyee'))
        {
            return new BaseObject(-1, '데이터가 변조되었습니다. 취소를 눌러서 뒤로 돌아가신 후 다시 시도해주세요.');
        }

        $oPointModel = &getModel('point'); //포인트 모듈을 불러오고
        $point = $oPointModel->getPoint($member_srl);
		
        //프로목록에 등록
		if($point < $es_point)
		{
			return new BaseObject(-1, '포인트가 부족합니다.');
		} else {
		//포인트 차감
        $oPointController = &getController('point');
        $oPointController->setPoint($member_srl, $es_point, 'minus');
        $args = new stdClass;
        $args->member_srl=$member_srl;
        $args->regdate=zDate(date("YmdHis"),"YmdHis");
        $args->enddate=zDate(date("YmdHis"),"YmdHis");
        $output=executeQuery('gmdata.insertPro',$args);
        //등록오류시
        if(!$output)
        {
            return new BaseObject(-1, '등록에 실패하였습니다 다시 시도해주세요.');
        }
		}
        
        $this->setRedirectUrl(getNotEncodedUrl('','mid','gmdata','act','dispGMdataPro'));
    }
	
	function procGMdataLoginSuccess($game_token, $member_srl)
	{
		$args = new stdClass;
		$args->gametoken = $game_token;
		$args->member_srl = $member_srl;
		
		executeQuery('gmdata.insertLogindata',$args);
		
		$this->setRedirectUrl("https://www.cookiee.net/login_success.php");
	}

	function procGMdataUpdateUserData()
    {
        if(!$this->grant->create_gameboard)
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }

        $member_srl = Context::get('member_srl');
        $user_srl = Context::get('user_srl');
        $game_token = Context::get('game_token');
        $user_status = Context::get('user_stat');
        $user_block_reason =  Context::get('block_reason');
        $user_block_etc = Context::get('block_date_etc');
        $user_name = Context::get('user_nick');
        $udata = Context::get('game_data');

        switch(intval($user_status)){
            case 1:
                $user_status_des = "정상 유저";
                break;

            case 2:
                $user_status_des = "차단 유저";
                break;

            case 3:
                $user_status_des = "영구 차단 유저";
                break;

            default:
                $user_status_des = "unknown";
                break;
        }

        $args = new stdClass;
        $args->usr_srl = $user_srl;
        $args->game_token = $game_token;
        $args->mb_status = '{"status":'.$user_status.',"des":"'.$user_status_des.'","block_reason":"'.$user_block_reason.'","block_term":"'.$user_block_etc.'"}';
        $args->name = $user_name;
        $args->datas = base64_encode($udata);
        $args->log_time = date("Y-m-d H:i:s");


        executeQuery("gmdata.updateUserDataAdm",$args);
        $this->setRedirectUrl(getNotEncodedUrl('','mid','gmdata','act','dispGMdataViewData','game_token',$game_token,'edit',$user_srl));
    }
}
