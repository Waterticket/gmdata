<?PHP

class gmdataModel extends gmdata
{
	var $config;

	/**
	 * @brief 초기화
	 */
	function init()
	{
	}

    /**
     * @brief 기본 설정 체크
     * @return object
     */
	function getConfig()
	{
		if(!$this->config)
		{
			$oModuleModel = getModel('module');
			$config = $oModuleModel->getModuleConfig('gmdata');

			if(!$config->free_max_board) $config->free_max_board = '3';
            if(!$config->pro_max_board) $config->pro_max_board = '100'; 
			if(!$config->free_max_data) $config->free_max_data = '100'; //최대 회원
            if(!$config->pro_max_data) $config->pro_max_data = '10000'; 
			if(!$config->free_max_data_length) $config->free_max_data_length = '768'; //최대 데이터 길이
            if(!$config->pro_max_data_length) $config->pro_max_data_length = '15000';
            if(!$config->pro_upgrade_cost) $config->pro_upgrade_cost = '5000';
			//if(!$config->) $config-> == '';
			$this->config = $config;
		}
		return $this->config;
	}

    /**
     * @brief 모듈 사활 체크
     * @param string $tmp_var
     * @return string
     */
	function isgmdata_alive($tmp_var = "")
    {
	    return "alive! : ".$tmp_var;
    }

	/**
	 * @brief 모듈의 존재를 나타내도록
     * @return object
	 */
	function getGMdataInfo()
	{
		$output = executeQuery('gmdata.getBoard'); 
		if(!$output->data->module_srl) return; 
		$oModuleModel = &getModel('module'); 
		$module_info = $oModuleModel->getModuleInfoByModuleSrl($output->data->module_srl); 

		return $module_info; 
	}
	/**
	 * @brief 회원이 관리하는 스코어보드 모두 출력
     * @param $member_srl
     * @return object
	 */
	function getUserBoard($member_srl)
	{
		$arg = new stdClass;
		$arg->member_srl = $member_srl;
		$output = executeQuery('gmdata.getUserBoard',$arg);
		return $output->data;
	}

	/**
	 * @brief 특정 보드의 스코어를 출력
     * @param $game_token
     * @return object
	 */
	function getdataBoard($game_token)
	{
		$args = new stdClass;
        $args->game_token = $game_token;
        //게임네임에 해당하는 토큰 검색
        $output = executeQuery('gmdata.getBoardInfo',$args);
        
        //게임이 존재하지 않을경우
        if(empty($output->data->game_token))
        {
            return -1;
        }
		
		return $output;
	}

    /**
     * @brief 게임 버전을 얻는다
     * @param $token
     * @return object
     */
	function getgameVersion($token)
	{
		$args = new stdClass;
        $args->game_token = $token;
        //게임네임에 해당하는 토큰 검색
        $output = executeQuery('gmdata.getBoardInfo',$args);
        
        //게임이 존재하지 않을경우
        if(empty($output->data->game_token))
        {
            $args = new stdClass;
            $args->status = -4;
            return $args;
        }
		
        /*
        $args = new stdClass;
        $args->game_token = $output->data->game_token;
        $args->game_version = $output->data->game_version;
		$args->game_showversion = $output->data->game_showversion;
		$args->game_minversion = $output->data->game_minversion;
		$args->game_update_link = $output->data->game_update_link;
		$args->game_update_name = $output->data->game_update_name;
        $args->status = 1;
        */

        $output->data->status = 1;
		return $output->data;
	}

    /**
     * @brief 게임의 데이터를 얻는다
     * @param $args (object)
     * @return object
     */
	function getgameDatas($args)
	{
        //게임네임에 해당하는 토큰 검색
        $output = executeQuery('gmdata.getDatas',$args);
        
		//데이터가 없을경우
		if(empty($output->data))
        {
            return -5;
        }
		
        //게임이 존재하지 않을경우
        if(empty($output->data->game_token))
        {
            return -4;
        }
		
        $puts = new stdClass;
        $puts->nickname = $output->data->name;
        $puts->datas = $output->data->datas;
		$puts->usr_status = $output->data->usr_status;
		return $puts;
	}

    /**
     * @brief 데이터를 전송한다
     * @param $args (object)
     * @return int
     */
	function sendDatas($args)
	{
        //게임토큰 유효여부 검색
        $is_valid_token = executeQuery('gmdata.checkToken',$args);
        if($is_valid_token->data->count<=0) {
            //토큰이 유효하지 않을경우
            return -2;
        }
		//해시값 체크
		$GetUsrSecret = executeQuery('gmdata.getUserSecret',$args);
		
        if($args->hash != sha1($args->datas.$GetUsrSecret->data->game_secret))
        {	//데이터 + 클라 시크릿
			//해시값이 일치하지 않을경우
			return -3;
		}
        $boardcnt = executeQuery('gmdata.checkBoardcnt',$args);

        if($boardcnt->data->count>0){
            executeQuery('gmdata.updateScore',$args);
        }else{
            $args->mb_status = '{"status":1,"des":"정상 유저"}';
            executeQuery('gmdata.insertScore',$args);
        }

        //스코어 등록
        return 1;
	}

    /**
     * @brief 채팅을 저장한다
     * @param $args (object)
     * @return int
     */
    function sendChats($args)
    {
        //게임토큰 유효여부 검색
        $is_valid_token = executeQuery('gmdata.checkToken',$args);
        if($is_valid_token->data->count<=0) {
            //토큰이 유효하지 않을경우
            return -2;
        }

        executeQuery('gmdata.insertChats',$args);

        //스코어 등록
        return 1;
    }

    /**
     * @brief 채팅 데이터를 보낸다
     * @param $game_token
     * @return int or object
     */
    function getChatDatas($game_token)
    {
        $args = new stdClass;
        $args->game_token = $game_token;
        //게임네임에 해당하는 토큰 검색
        $output = executeQuery('gmdata.getChats',$args);

        //데이터가 없을경우
        if(empty($output->data))
        {
            return -5;
        }

        return $output->data;
    }

    /**
     * @brief 로그를 집어넣는다
     * @param $args (object)
     * @return int
     */
	function insertLogs($args)
	{
		$puts = new stdClass; 
		$puts->game_token = $args->game_token;
		$puts->log_status = $args->log_stat;
		$puts->member_srl = $args->usr_srl;
		$puts->log_time = $args->log_time;
		
		if($args->log_stat == 1){
			$puts->logs = "nickname -> ".$args->name." , game datas inserted. : ".substr($args->datas, 0, 300);
		}else if($args->log_stat == 2){
			$puts->logs = "nickname -> ".$args->name." , game datas updated. : ".substr($args->datas, 0, 300);
		}else{
			$puts->logs = "unknown type log stats. nickname -> ".$args->name;
		}
		
		executeQuery('gmdata.insertlog',$puts);
		return 1;
	}
	
	function return_member_srl($hash)
	{
		
	}
    
    /**
	 * @brief 프로 여부 체크
     * @param $member_srl
     * @return boolean
	 */
	function checkIfPro($member_srl)
	{
		$arg = new stdClass;
		$arg->member_srl = $member_srl;
		$output = executeQuery('gmdata.checkPro',$arg);
        if($output->data->count > 0 )
        {
            return true;
        }
        else
        {
            return false;
        }
	}
	
	/**
	 * @brief 로그인 데이터가 유효한지 체크
     * @param $game_token
     * @param $member_srl
     * @return boolean
	 */
	function checkLoginAvali($game_token, $member_srl)
	{
		$arg = new stdClass;
		$arg->gametoken = $game_token;
		$arg->member_srl = $member_srl;
		
		$output = executeQuery('gmdata.checkUserSrl',$arg);
        if($output->data->count > 0 )
        {
            return true;
        }
        else
        {
            return false;
        }
	}
    
    /**
	 * @brief 보드가 존재하는지 체크
     * @param $game_name
     * @return boolean
	 */
	function checkIfBoardExists($game_name)
	{
		$arg = new stdClass;
		$arg->game_name = $game_name;
		$output = executeQuery('gmdata.checkBoardExists',$arg);
		if($output->data->count > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
	}

    /**
     * @brief 더 이상의 보드 생성이 가능한지 체크
     * @param $member_srl
     * @param $max_board
     * @return bool
     */
    function checkIfavailable($member_srl, $max_board)
	{
		$arg = new stdClass;
		$arg->member_srl = $member_srl;
		$output = executeQueryArray('gmdata.getUserBoard',$arg);
        $output = count($output->data);
		if($output < $max_board)
        {
            return true;
        }
        else
        {
            return false;
        }
	}

    /**
     * @brief 로그인후 데이터 체크
     * @param $game_token
     * @param $hash
     * @param $member_srl
     * @return bool
     */
	function GMdataLoginSuccess($game_token, $hash, $member_srl){
		$args = new stdClass;
		$args->game_token = $game_token;
		$args->logtoken = $hash;
		$args->member_srl = $member_srl;
		
		$output = executeQuery('gmdata.checkLoginData',$args);
		if($output->data->count > 0 ){
			executeQuery('gmdata.insertLoginTmpdata',$args);
		}else{
			executeQuery('gmdata.insertLogindata',$args);
			executeQuery('gmdata.insertLoginTmpdata',$args);
		}
		
		$output = executeQuery('gmdata.checkLoginData',$args);
		if($output->data->count > 0 ){
			return true;
		}else{
			return false;
		}
		
	}

    /**
     * @brief 소셜로그인 여부 체크
     * @param $game_token
     * @param $hash
     * @return int
     */
	function GMdataGmsLogin($game_token, $hash){
		$return_data = -1;
		$args = new stdClass;
		$args->game_token = $game_token;
		$args->logtoken = $hash;
		
		$output = executeQuery('gmdata.checkLoginTmpdata',$args);
		if(empty($output->data->member_srl)){
			$return_data = -1;
		}else{
			$return_data = $output->data->member_srl;
		}
		return $return_data;
	}

    /**
     * @brief srl로 로그인 여부 체크
     * @param $game_token
     * @param $member_srl
     * @return bool
     */
	function GMdataGmsLogin_srl($game_token, $member_srl){
		$return_data = false;
		$args = new stdClass;
		$args->game_token = $game_token;
		$args->member_srl = $member_srl;
		
		$output = executeQuery('gmdata.checkLogindata',$args);
		if($output->data->count > 0){
			$return_data = true;
		}else{
			$return_data = false;
		}
		
		return $return_data;
	}
}
