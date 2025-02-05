<?php
/*
 *********************************************************************************************************
 * daloRADIUS - RADIUS Web Platform
 * Copyright (C) 2007 - Liran Tal <liran@enginx.com> All Rights Reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *********************************************************************************************************
 *
 * Authors:    Liran Tal <liran@enginx.com>
 *             Filippo Lauria <filippo.lauria@iit.cnr.it>
 *
 *********************************************************************************************************
 */

    include ("library/checklogin.php");
    $operator = $_SESSION['operator_user'];

    include('library/check_operator_perm.php');


    include 'library/opendb.php';

    isset($_REQUEST['name']) ? $name = $_REQUEST['name'] : $name = "";
    isset($_REQUEST['macaddress']) ? $macaddress = $_REQUEST['macaddress'] : $macaddress = "";
    isset($_REQUEST['geocode']) ? $geocode = $_REQUEST['geocode'] : $geocode = "";
    isset($_REQUEST['owner']) ? $owner = $_REQUEST['owner'] : $owner = "";
    isset($_REQUEST['email_owner']) ? $email_owner = $_REQUEST['email_owner'] : $email_owner = "";
    isset($_REQUEST['manager']) ? $manager = $_REQUEST['manager'] : $manager = "";
    isset($_REQUEST['email_manager']) ? $email_manager = $_REQUEST['email_manager'] : $email_manager = "";
    isset($_REQUEST['address']) ? $address = $_REQUEST['address'] : $address = "";
    isset($_REQUEST['company']) ? $company = $_REQUEST['company'] : $company = "";
    isset($_REQUEST['phone1']) ? $phone1 = $_REQUEST['phone1'] : $phone1 = "";
    isset($_REQUEST['phone2']) ? $phone2 = $_REQUEST['phone2'] : $phone2 = "";
    isset($_REQUEST['hotspot_type']) ? $hotspot_type = $_REQUEST['hotspot_type'] : $hotspot_type = "";
    isset($_REQUEST['companywebsite']) ? $companywebsite = $_REQUEST['companywebsite'] : $companywebsite = "";
    isset($_REQUEST['companyphone']) ? $companyphone = $_REQUEST['companyphone'] : $companyphone = "";
    isset($_REQUEST['companyemail']) ? $companyemail = $_REQUEST['companyemail'] : $companyemail = "";
    isset($_REQUEST['companycontact']) ? $companycontact = $_REQUEST['companycontact'] : $companycontact = "";

    $edit_hotspotname = $name; //feed the sidebar variables    

    $logAction = "";
    $logDebugSQL = "";

    if (isset($_REQUEST['submit'])) {

        $name = $_REQUEST['name'];
        $macaddress = $_REQUEST['macaddress'];
        $geocode = $_REQUEST['geocode'];

        if (trim($name) != "") {

            $currDate = date('Y-m-d H:i:s');
            $currBy = $_SESSION['operator_user'];

            $sql = "UPDATE ".$configValues['CONFIG_DB_TBL_DALOHOTSPOTS']." SET ".
            " mac='".$dbSocket->escapeSimple($macaddress)."', ".
            " geocode='".$dbSocket->escapeSimple($geocode).    "', ".
            " owner='".$dbSocket->escapeSimple($owner)."', ".
            " email_owner='".$dbSocket->escapeSimple($email_owner)."', ".
            " manager='".$dbSocket->escapeSimple($manager)."', ".
            " email_manager='".$dbSocket->escapeSimple($email_manager)."', ".
            " address='".$dbSocket->escapeSimple($address)."', ".
            " company='".$dbSocket->escapeSimple($company)."', ".
            " phone1='".$dbSocket->escapeSimple($phone1)."', ".
            " phone2='".$dbSocket->escapeSimple($phone2)."', ".
            " type='".$dbSocket->escapeSimple($hotspot_type)."', ".
            " companywebsite='".$dbSocket->escapeSimple($companywebsite)."' , ".
            " companyemail='".$dbSocket->escapeSimple($companyemail)."' , ".
            " companycontact='".$dbSocket->escapeSimple($companycontact)."' , ".
            " companyphone='".$dbSocket->escapeSimple($companyphone)."' , ".
            " updatedate='$currDate', updateby='$currBy' ".
            " WHERE name='".$dbSocket->escapeSimple($name)."'";
            $res = $dbSocket->query($sql);
            $logDebugSQL = "";
            $logDebugSQL .= $sql . "\n";
            
            $successMsg = "Updated attributes for: <b> $name </b>";
            $logAction .= "Successfully updated attributes for hotspot [$name] on page: ";
            
        } else {
            $failureMsg = "no hotspot name was entered, please specify a hotspot name to edit";
            $logAction .= "Failed updating attributes for hotspot [$name] on page: ";
        }
        
    }
    

    // fill-in username and password in the textboxes
    $sql = "SELECT * FROM ".$configValues['CONFIG_DB_TBL_DALOHOTSPOTS']." WHERE name='".$dbSocket->escapeSimple($name)."'";
    $res = $dbSocket->query($sql);
    $logDebugSQL .= $sql . "\n";

    $row = $res->fetchRow();
    $macaddress = $row[2];
    $geocode = $row[3];
    $owner = $row[4];
    $email_owner = $row[5];
    $manager = $row[6];
    $email_manager = $row[7];
    $address = $row[8];
    $company = $row[9];
    $phone1 = $row[10];
    $phone2 = $row[11];
    $hotspot_type = $row[12];
    $companywebsite = $row[13];
    $companyemail = $row[14];
    $companycontact = $row[15];
    $companyphone = $row[16];
    $creationdate = $row[17];
    $creationby = $row[18];
    $updatedate = $row[19];
    $updateby = $row[20];

    include 'library/closedb.php';


    if (trim($name) == "") {
        $failureMsg = "no hotspot name was entered, please specify a hotspot name to edit</b>";
    }

    include_once('library/config_read.php');
    $log = "visited page: ";

    include_once("lang/main.php");
    
    include("library/layout.php");

    // print HTML prologue
    $extra_css = array(
        // css tabs stuff
        "css/tabs.css"
    );
    
    $extra_js = array(
        // js tabs stuff
        "library/javascript/tabs.js"
    );
    
    $title = t('Intro','mnghsedit.php');
    $help = t('helpPage','mnghsedit');
    
    print_html_prologue($title, $langCode, $extra_css, $extra_js);

    if (isset($name)) {
        $title .= ":: $name";
    } 

    include("menu-mng-hs.php");
    echo '<div id="contentnorightbar">';
    print_title_and_help($title, $help);
    
    include_once('include/management/actionMessages.php');
    
    // set navbar stuff
    $navbuttons = array(
                          'HotspotInfo-tab' => t('title','HotspotInfo'),
                          'ContactInfo-tab' => t('title','ContactInfo'),
                       );

    print_tab_navbuttons($navbuttons);
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">


    <div class="tabcontent" id="HotspotInfo-tab" style="display: block">


    <fieldset>

        <h302> <?php echo t('title','HotspotInfo'); ?> </h302>
        <br/>

        <ul>

            <li class='fieldset'>
            <label for='name' class='form'><?php echo t('all','HotSpotName') ?></label>
            <input disabled name='name' type='text' id='name' value='<?php echo $name ?>' tabindex=100 />
            </li>

            <li class='fieldset'>
            <label for='macaddress' class='form'><?php echo t('all','MACAddress') ?></label>
            <input name='macaddress' type='text' id='macaddress' value='<?php echo $macaddress ?>' tabindex=101 />
            <img src='images/icons/comment.png' alt='Tip' border='0' onClick="javascript:toggleShowDiv('hotspotMacaddressTooltip')" /> 
            
            <div id='hotspotMacaddressTooltip'  style='display:none;visibility:visible' class='ToolTip'>
                <img src='images/icons/comment.png' alt='Tip' border='0' />
                <?php echo t('Tooltip','hotspotMacaddressTooltip') ?>
            </div>
            </li>

            <li class='fieldset'>
            <label for='geocode' class='form'><?php echo t('all','Geocode') ?></label>
            <input name='geocode' type='text' id='geocode' value='<?php echo $geocode ?>' tabindex=102 />
            <img src='images/icons/comment.png' alt='Tip' border='0' onClick="javascript:toggleShowDiv('geocodeTooltip')" /> 
            
            <div id='geocodeTooltip'  style='display:none;visibility:visible' class='ToolTip'>
                <img src='images/icons/comment.png' alt='Tip' border='0' />
                <?php echo t('Tooltip','geocodeTooltip') ?>
            </div>
            </li>

            <li class='fieldset'>
            <br/>
            <hr><br/>
            <input type='submit' name='submit' value='<?php echo t('buttons','apply') ?>' tabindex=10000
                class='button' />
            </li>

        </ul>

    </fieldset>

    <input type=hidden value="<?php echo $name ?>" name="name"/>

</div>

<div class="tabcontent" id="ContactInfo-tab">

<?php
    include_once('include/management/contactinfo.php');
?>

        </div>

</form>

        </div><!-- #contentnorightbar -->
        
        <div id="footer">
<?php
    include('include/config/logging.php');
    include('page-footer.php');
?>
        </div><!-- #footer -->
    </div>
</div>

</body>
</html>
