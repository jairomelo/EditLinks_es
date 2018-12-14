<?php

/* 
 * E-man Plugin
 *
 * Functions to customize Omeka for the E-man Project
 *
 */

class EnlaceEditarPlugin extends Omeka_Plugin_AbstractPlugin 
{  

  protected $_filters = array(
   		'public_navigation_admin_bar',  		
  );

  public function filterPublicNavigationAdminBar($nav)
  {
    	if ($currentUser = current_user()) {
  			$params = Zend_Controller_Front::getInstance()->getRequest()->getParams();
  	  	$editLink = "";	
  	  	if (isset($params['controller'])) {
   	      $nomContenu = ' este elemento';
  	  		if (in_array($params['controller'], array('page', 'items', 'collections', 'files', 'eman', 'exhibits')) && $params['action'] <> 'browse') {
  	  			if (in_array($currentUser->role, array('super', 'admin', 'editor'))) {
    	  			if (isset($params['id'])) {
                $editPart = '/edit/'. $params['id'];
      	  	  }
  	  				if ($params['controller'] == 'eman') {
  	  					$controller = explode("-", $params['action']);
  	  					$controller = $controller[0];
                $editPart = '/edit/'. $params['id'];              
   	  				} else {
  	  					$controller = $params['controller'];
  	  				}
  	  				if(isset($params['module'])) {  	  				
  	  				  // Simple Pages
  	  				  if ($params['module'] == 'simple-pages') {
      	  				$controller = 'simple-pages/index';
      	  				$editPart = '/edit/id/' . $params['id'];
                  $nomContenu = ' esta página';    	  				
    	  				}
  		  				// Exhibit Builder
    	  				if ($params['module'] == 'exhibit-builder') {
      	  				$controller = 'exhibits';
      	  				$db = get_db();
                  $page = $db->query("SELECT id FROM `$db->ExhibitPage` WHERE slug = '" . $params['page_slug_1'] . "'")->fetchAll();
                  if ($page[0]['id']) {
      	  					$editPart = '/edit-page/' . $page[0]['id'];
      	  					$nomContenu = ' esta página de exhibición';      	  					
                  } else {
                  	$front = $db->query("SELECT id FROM `$db->Exhibits` WHERE slug = '" . $params['slug'] . "'")->fetchAll();
                  	$editLink = '/exhibits/edit/' . $front[0]['id'];
                  	$nomContenu = ' esta exhibición';                  	
                  }
   	  				
    	  				}
  	  				} 
  	  				if ($controller && $editPart) {
      					$editLink = $controller . $editPart;
  	  				}
  	  			}
  	  		}	  			 
  	  	} else {
    		  return $nav;
        }   	  	
    	}    
    	if ($editLink != "") {
      	$nav[] = array(
          'label' => __('Editar ' . $nomContenu),
          'uri' => url('/admin/' . $editLink)
        );      	
    	}
      return $nav;
  }
}