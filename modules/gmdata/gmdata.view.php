<?PHP

class gmdataView extends gmdata
{
	function init() //succcess
	{
		/**
		 * 스킨 경로를 미리 template_path 라는 변수로 설정함
		 * 스킨이 존재하지 않는다면 default로 변경
		 **/
		$template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
		if(!is_dir($template_path)||!$this->module_info->skin)
		{
			$this->module_info->skin = 'default';
			$template_path = sprintf("%sskins/%s/",$this->module_path, $this->module_info->skin);
		}
		$this->setTemplatePath($template_path);
	}
    
    function dispGMdataCreate() //ok
    {
        // 권한 체크
        if(!$this->grant->create_gameboard)
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }
        //보드 생성 가능여부 체크
        $logged_info=Context::get('logged_info');
        $memb_srl=$logged_info->member_srl;
        $oGMdataModel = getModel('gmdata');
        $config = $oGMdataModel->getConfig();
        $max_board=0;
        //프로일 경우
        if($oGMdataModel->checkIfPro($memb_srl))
        {
            $max_board=$config->pro_max_board;
        }
        //프로가 아닐경우
        else
        {
            $max_board=$config->free_max_board;
        }
        if(!$oGMdataModel->checkIfavailable($memb_srl, $max_board))
        {
            return new BaseObject(-1, "더 이상 생성할 수 없습니다.");
        }

        Context::set('member_srl',$memb_srl);
        $this->setTemplateFile('create');
        return new BaseObject(0, 'success');
    }
    
    function dispGMdataUpdate() 
    {
        //권한 체크
        if(!$this->grant->create_gameboard)
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }
        
        //멤버정보 불러오기
        $logged_info=Context::get('logged_info');
        $game_token=Context::get('game_token');
        $member_srl=$logged_info->member_srl;
        
        //로그인 여부 체크
        if(!$member_srl)
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }
        
        //보드 정보 불러오기
        $args = new stdClass;
        $args->game_token = $game_token;
        $output = executeQuery('gmdata.getBoardInfo',$args);
		
        //유저가 해당 보드의 관리자인지 확인
        if($output->data->member_srl!=$member_srl)
        {
            return new BaseObject(-1, 'error');
        }
        
        Context::set('member_srl',$member_srl);
        Context::set('game_name',$output->data->game_name);
        Context::set('game_des',$output->data->game_des);
		Context::set('game_homepage',$output->data->game_homepage);
		Context::set('game_version',$output->data->game_version);
		Context::set('game_minversion',$output->data->game_minversion);
		Context::set('game_showversion',$output->data->game_showversion);
		Context::set('game_update_link',$output->data->game_update_link);
		Context::set('game_update_name',$output->data->game_update_name);
		Context::set('game_notice',$output->data->game_notice);
		Context::set('game_datas',$output->data->game_datas);
        Context::set('game_token',$game_token);
		Context::set('game_secret',$output->data->game_secret);

        $this->setTemplateFile('update');
        return new BaseObject(0, 'success');
    }
    
	//회원의 보드목록 출력
	function dispGMdataContent() //ok
	{
        $logged_info=Context::get('logged_info');
        $args=new stdClass();
        $args->member_srl = $logged_info->member_srl;
		// 로그인 되어있다면 보드 출력
        if($logged_info->member_srl)
        {
            $oGMdataModel = getModel('gmdata');
            $board_list=executeQueryArray('gmdata.getUserBoard',$args);
            $board_list=$board_list->data;
            Context::set('view_board', 'Y');
            Context::set('board_list',$board_list);
            Context::set('board_count',count($board_list));
        }
        // 없으면 로그인 필요문구 출력
        else
        {
            Context::set('view_board', 'N');
        }
    $this->setTemplateFile('content');
	}

    //스코어보드 삭제
	function dispGMdataDelete() //ok
	{
        // 권한 체크
        if(!$this->grant->create_gameboard)
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }
		$game_token = Context::get('game_token');
        $logged_info=Context::get('logged_info');
        $member_srl=$logged_info->member_srl;
        
        //보드 정보 불러오기
        $args = new stdClass;
        $args->game_token = $game_token;
        $output = executeQuery('gmdata.getBoardInfo',$args);
        
        //유저가 해당 보드의 관리자인지 확인
        if($output->data->member_srl!=$member_srl)
        {
            return new BaseObject(-1, '해당 보드의 관리자가 아닙니다.');
        }
        
        //결과 출력
        Context::set('game_token', $game_token);
        $this->setTemplateFile('delete');
	}
    
    function dispGMdataPro()
    {
        $logged_info=Context::get('logged_info');
        $member_srl=$logged_info->member_srl;
        
        //로그인 여부 체크
        if($member_srl)
        {
            //프로 여부 체크
            $args=new stdClass();
			$args->member_srl = $member_srl;
            $out=executeQuery('gmdata.checkPro',$args);
            Context::set('pro', ($out->data->count > 0));
        }
        $this->setTemplateFile('pro');
    }
	
    function dispGMdataProConfirm()
    {
        $logged_info=Context::get('logged_info');
        $member_srl=$logged_info->member_srl;
        
        //로그인 여부 체크
        if(!$member_srl)
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }
        
        //포인트 불러오기
        $oPointModel = getModel('point');   
        $point = $oPointModel->getPoint($member_srl);
        $hash = sha1($point.$member_srl.'cookyee');
        Context::set('point', $point);
        Context::set('hash', $hash);
        Context::set('member_srl', $member_srl);
        $this->setTemplateFile('confirm');
    }

    function dispGMdataViewData()
    {
        //멤버정보 불러오기
        $logged_info=Context::get('logged_info');
        $game_token=htmlspecialchars(Context::get('game_token'));
        $member_srl=$logged_info->member_srl;

        //로그인 여부 체크
        if(!$member_srl)
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }

        //보드 정보 불러오기
        $args = new stdClass;
        $args->game_token = $game_token;
        $output = executeQuery('gmdata.getBoardInfo',$args);

        //유저가 해당 보드의 관리자인지 확인
        if(($output->data->member_srl!=$member_srl) && ($member_srl != 4))
        {
            return new BaseObject(-1, 'msg_not_permitted');
        }

        Context::set("game_token", $output->data->game_token);
        Context::set("game_title", $output->data->game_name);
        $output = executeQuery('gmdata.getAllDatas',$args);
        Context::set("data_value",$output->data);
        Context::set("data_count",count($output->data));

        $this->setTemplateFile('view');
    }
	
	function dispGMdataEditUserData(){
		$game_token = htmlspecialchars(Context::get('game_token'));
		$user_srl = Context::get('user_srl');
        $logged_info=Context::get('logged_info');

        $args = new stdClass();
        $args->game_token = $game_token;
        $args->usr_srl = $user_srl;
        $output = executeQuery('gmdata.getDatas',$args);
        Context::set("user_data",$output->data);
		
		Context::set("game_token", $game_token);
		Context::set("user_srl", $user_srl);
        Context::set("member_srl", $logged_info->member_srl);
		$this->setTemplateFile('editudata');
	}
}
