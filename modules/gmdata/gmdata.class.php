<?PHP

class gmdata extends ModuleObject 
{
    /**
    * @brief 모듈 설치
    **/
	function moduleInstall()
	{
		//moduleController 등록
		$oModuleController = getController('module');
		$oModule = getModel('module');
		$module_info = $oModule->getModuleInfoByMid('gmdata');
		if($module_info->module_srl)
		{
			//이미 만들어진 gmdata mid가 있다면
			if($module_info->module != 'gmdata')
			{
				return new BaseObject(1,'gmdata_error_mid');
			}
		}
		else
		{
			/*Create mid*/
			$oModuleController = getController('module');
			$args = new stdClass;
			$args->mid = 'gmdata';
			$args->module = 'gmdata';
			$args->browser_title = '쿠키 게임 관리 시스템';
			$args->site_srl = 0;
			$args->skin = 'default';
			$args->order_type = 'desc';
			$output = $oModuleController->insertModule($args);
		}
	}

    /**
    * @brief 업데이트 할 만한 게 있는지 체크
    **/
	function checkUpdate()
	{
		return false;
	}

	/**
	* @brief 모듈 업데이트
	**/
	function moduleUpdate() {
		return new BaseObject();
	}
    /**
	 * @brief Receives a document title and returns an URL firendly name
	 * @developer NHN (developers@xpressengine.com)
	 * @access public
	 * @param $entry_name string
	 * @return string 
	 */
	function beautifyEntryName($entry_name) 
	{
		$entry_name = strip_tags($entry_name); 
		$entry_name = html_entity_decode($entry_name); 
		$entry_name = preg_replace($this->omitting_characters, $this->replacing_characters, $entry_name); 
		$entry_name = preg_replace('/[_]+/', '_', $entry_name); 
		$entry_name = strtolower($entry_name); 
		return $entry_name;
	}
    
    /**
	 * @brief Returns qualified internal link, given an alias or doc title
	 * @developer Corina Udrescu (xe_dev@arnia.ro)
	 * @access public
	 * @param $document_name string
	 * @return string
	 */
	function getFullLink($document_name) 
	{
		return getUrl('', 'gmdata', $document_name, 'game_name', '');
	}
}
