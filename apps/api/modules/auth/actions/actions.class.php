<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * auth actions.
 *
 * @package    OpenPNE
 * @subpackage auth
 * @author     tejima@tejimaya.com
 * @version    SVN: $Id: actions.class.php 9301 2008-05-27 01:08:46Z dwhittle $
 */
class authActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    $mail_address = $request->getParameter("address",null);
    $password = $request->getParameter("password",null);
    
    $mc1_list = Doctrine_Query::create()->from('MemberConfig mc')->where('mc.name = ?',"pc_address")->andWhere("mc.value = ?",$mail_address)->limit(1)->fetchArray();
    $mc2_list = Doctrine_Query::create()->from('MemberConfig mc')->where('mc.name = ?',"mobile_address")->andWhere("mc.value = ?",$mail_address)->limit(1)->fetchArray();
    if(sizeof($mc1_list) == 1)
    {
      $member_id = $mc1_list[0]['member_id'];
    }
    if(sizeof($mc2_list) == 1)
    {
      $member_id = $mc2_list[0]['member_id'];
    }
    $password_list = Doctrine_Query::create()->from('MemberConfig mc')->where('mc.name = ?',"password")->andWhere("mc.member_id = ?",$member_id)->limit(1)->fetchArray();
    if(sizeof($password_list) == 1)
    {
      if($password == $password_list[0]["value"])
      {
        $result = array("member_id" => (int)$member_id);
      }
      else
      {
        $result = array("member_id" => -1);
      }
    }
    else
    {
      $result = array("member_id" => -1);
    }
    return $this->renderText(json_encode($result));
  }
}
