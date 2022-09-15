<?php
namespace Drupal\miniorange_saml;

class mo_saml_visualTour {

    public static function genArray($overAllTour = 'tabTour'){
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $exploded = explode('/', $link);
        $getPageName = end($exploded);
        $Tour_Token = \Drupal::config('miniorange_saml.settings')->get('mo_saml_tourTaken_' . $getPageName);
        if($overAllTour == 'overAllTour') {
            $getPageName = 'overAllTour';
        }

        $https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
        $request_scheme = isset($_SERVER['REQUEST_SCHEME']) ? $_SERVER['REQUEST_SCHEME'] : $https;

        $moTourArr = array (
            'pageID' => $getPageName,
            'tourData' => mo_saml_visualTour::getTourData($getPageName),
            'tourTaken' => 'CustomCertificate' === $getPageName || 'Licensing' === $getPageName ? TRUE : $Tour_Token,
            'addID' => mo_saml_visualTour::addID(),
            'pageURL' => $request_scheme . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        );

        \Drupal::configFactory()->getEditable('miniorange_saml.settings')->set('mo_saml_tourTaken_' . $getPageName, TRUE)->save();
        $moTour = json_encode($moTourArr);
        return $moTour;
    }

    public static function addID()
    {
        $idArray = array(
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(1)',
                'newID'     =>'mo_vt_idp_setup',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(2)',
                'newID'     =>'mo_vt_sp_setup',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(3)',
                'newID'     =>'mo_vt_mapping',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(4)',
                'newID'     =>'mo_vt_sign_sett',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(5)',
                'newID'     =>'mo_vt_advance_settings',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(6)',
                'newID'     =>'mo_vt_licensing',
            ),
            array(
                'selector'  =>'li.tabs__tab:nth-of-type(7)',
                'newID'     =>'mo_vt_account',
            ),
            array(
                'selector'  =>'table',
                'newID'     =>'mo_idp_url_table',
            ),
        );
        return $idArray;
    }
    public static function getTourData($pageID) {

        $tourData = array();
        $link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $exploded = explode('/', $link);
        $getPageName = end($exploded);
        $Tour_Token = \Drupal::config('miniorange_saml.settings')->get('mo_saml_tourTaken_' . $getPageName);
        $tab_index = ($Tour_Token == 0 || $Tour_Token == FALSE) ? 'idp_setup' : 'idp_tab';

        $tourData['idp_setup'] = array(

            0 => array(
                'targetE'       =>  'mo_idp_url_table',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>SP Metadata URLs</h1>'),
                'contentHTML'   =>  t('You can manually configure your Identity Provider using the information given here.'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'big',
            ),
            1 => array(
                'targetE'       =>  'download_metadata_xml_file',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>Metadata XML File</h1>'),
                'contentHTML'   =>  t('Provide this <b>Metadata File</b> to configure your Identity Provider'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'medium',
            ),
            2 => array(
                'targetE'       =>  'idp_metadata_url',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>SP Metadata URL</h1>'),
                'contentHTML'   =>  t('Provide this <b>Metadata URL</b> to configure your Identity Provider'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'medium',
            ),
            3 => array
            (
                'targetE'       => 'mo_guide_vt',
                'pointToSide'   => 'right',
                'titleHTML'     => t('<h1>Documentation</h1>'),
                'contentHTML'   => t('To see detailed documentation of how to configure Drupal SAML SP with any Identity Provider.'),
                'ifNext'        => true,
                'buttonText'    => 'End Tour',
                'cardSize'      => 'largemedium',
                'action'        => '',
                'ifskip'        =>  'hidden',
            ),
        );

        $tourData[$tab_index] = array(
            0 => array(
                'targetE'       => 'mo_vt_idp_setup',
                'pointToSide'   => 'up',
                'titleHTML'     => t('<h1>Service Provider Metadata</h1>'),
                'contentHTML'   => t('This tab provides details to configure your <b>Identity Provider</b>.'),
                'ifNext'        => true,
                'buttonText'    => 'Next',
                'cardSize'      => 'largemedium',
                'action'        => '',
            ),
            1 => array(
                'targetE'       => 'mo_vt_sp_setup',
                'pointToSide'   => 'up',
                'titleHTML'     => t('<h1>Service Provider Setup</h1>'),
                'contentHTML'   => t('Configure this tab using Identity provider information which you get from <b>IDP-Metadata XML</b>.'),
                'ifNext'        => true,
                'buttonText'    => 'Next',
                'cardSize'      => 'big',
                'action'        => '',
            ),
            2 => array(
                'targetE'       => 'mo_vt_mapping',
                'pointToSide'   => 'up',
                'titleHTML'     => t('<h1>Attribute/Role Mapping Tab</h1>'),
                'contentHTML'   => t('In this tab you can find <b>attribute mapping</b>, <b>role mapping</b> and more.'),
                'ifNext'        => true,
                'buttonText'    => 'Next',
                'cardSize'      => 'largemedium',
                'action'        => '',
            ),
            3 => array(
                'targetE'       =>  'mo_idp_url_table',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>SP Metadata URLs</h1>'),
                'contentHTML'   =>  t('You can manually configure your Identity Provider using the information given here.'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'big',
            ),
            4 => array(
                'targetE'       =>  'idp_metadata_url',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>SP Metadata URL</h1>'),
                'contentHTML'   =>  t('Provide this <b>Metadata URL</b> to configure your Identity Provider'),
                'ifNext'        =>  true,
                'buttonText'    =>  'End Tour',
                'cardSize'      =>  'medium',
            ),
        );

        $tourData['overAllTour'] = array(
            0 => array(
                'targetE'       => 'mo_vt_idp_setup',
                'pointToSide'   => 'up',
                'titleHTML'     => t('<h1>Service Provider Metadata</h1>'),
                'contentHTML'   => t('This tab provides details to configure your <b>Identity Provider</b>.'),
                'ifNext'        => true,
                'buttonText'    => 'Next',
                'cardSize'      => 'largemedium',
                'action'        => '',
            ),
            1 => array(
                'targetE'       => 'mo_vt_sp_setup',
                'pointToSide'   => 'up',
                'titleHTML'     => t('<h1>Service Provider Setup</h1>'),
                'contentHTML'   => t('Configure this tab using Identity provider information which you get from <b>IDP-Metadata XML</b>.'),
                'ifNext'        => true,
                'buttonText'    => 'Next',
                'cardSize'      => 'big',
                'action'        => '',
            ),
            2 => array(
                'targetE'       => 'mo_vt_mapping',
                'pointToSide'   => 'up',
                'titleHTML'     => t('<h1>Attribute/Role Mapping Tab</h1>'),
                'contentHTML'   => t('In this tab you can find <b>attribute mapping</b>, <b>role mapping</b> and more.'),
                'ifNext'        => true,
                'buttonText'    => 'End Tour',
                'cardSize'      => 'big',
                'action'        => '',
                'ifskip'        =>  'hidden',
            ),
        );

        $tourData['sp_setup'] = array(
            0 =>    array(
                'targetE'       =>  'edit-mo-saml-idp-setup',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>Upload Your Metadata</h1>'),
                'contentHTML'   =>  t('If you have a metadata <i>URL</i> or <i>file</i> provided by your IDP, click on the <b>Upload IDP Metadata</b> or you can configure the module manually.'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'big',
                'action'        =>  '',
            ),
            1 =>    array(
                'targetE'       =>  'miniorange_saml_idp_name_div',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>Identity Provider Name</h1>'),
                'contentHTML'   =>  t('Enter appropriate name for your Identity Provider'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'medium',
                'action'        =>  '',
            ),
            2 =>    array(
                'targetE'       =>  'miniorange_saml_idp_issuer_div',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>IdP Entity ID</h1>'),
                'contentHTML'   =>  t('You can find the <b>IDP EntityID/Issuer</b> in Your IdP-Metadata XML file enclosed in <b>EntityDescriptor</b> tag having attribute as entityID.'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'big',
                'action'        =>  '',
            ),
            3 =>    array(
                'targetE'       =>  'miniorange_saml_idp_login_url_start',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>Login URL</h1>'),
                'contentHTML'   =>  t('You can find the <b>SAML Login URL</b> in Your IdP-Metadata XML file enclosed in <b>SingleSignOnService</b> tag (Binding type: HTTP-Redirect)'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'big',
                'action'        =>  '',
            ),
            4 =>    array(
                'targetE'       =>  'miniorange_saml_idp_x509_certificate_start',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>x.509 Certificate</h1>'),
                'contentHTML'   =>  t('Public key of your IDP to read the signed SAML Assertion/Response'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'medium',
                'action'        =>  '',
            ),
            5 =>    array(
                'targetE'       =>  'enable_login_with_saml',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>Enable login with SAML</h1>'),
                'contentHTML'   =>  t('Enable the checkbox if you want to enable SSO login with IdP credentials.'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'largemedium',
                'action'        =>  '',
            ),
            6 =>    array(
                'targetE'       => 'mo_guide_vt',
                'pointToSide'   => 'right',
                'titleHTML'     => t('<h1>Documentation</h1>'),
                'contentHTML'   => t('To see detailed documentation of how to configure Drupal SAML SP with any Identity Provider.'),
                'ifNext'        => true,
                'buttonText'    => 'End Tour',
                'cardSize'      => 'largemedium',
                'action'        => '',
            ),
        );

        $tourData['Mapping'] = array(
            0 =>    array(
                'targetE'       =>  'mo_saml_id_role_mapping_v_tour',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>Role Mapping</h1>'),
                'contentHTML'   =>  t('Check this option if you want to enable <b>Role Mapping</b>.'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'medium',
                'action'        =>  '',
            ),
            1 =>    array(
                'targetE'       =>  'Default_Mapping',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>Default Group</h1>'),
                'contentHTML'   =>  t('You can select default group for the users.'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'medium',
                'action'        =>  '',
            ),
            2 =>    array(
                'targetE'       =>  'edit-mo-saml-custom-attribute-mapping',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>Custom attribute mapping</h1>'),
                'contentHTML'   =>  t('You can map custom/additional attributes here.'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'medium',
                'action'        =>  '',
            ),
            3=>    array(
                'targetE'      =>   'edit-mo-saml-custom-role-mapping',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>Custom Role Mapping</h1>'),
                'contentHTML'   =>  t('You can map IdP roles to Your SP roles here.'),
                'ifNext'        =>   true,
                'buttonText'    =>  'End Tour',
                'cardSize'      =>  'medium',
                'action'        =>  '',
                'ifskip'        =>  'hidden',

            ),
        );

        $tourData['AdvanceSettings'] = array(
            0 =>    array(
                'targetE'       =>  'edit-mo-saml-import-export-configurations',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>Import/Export Configurations</h1>'),
                'contentHTML'   =>  t('You can download module configuration file from here.'),
                'ifNext'        =>  true,
                'buttonText'    =>  'Next',
                'cardSize'      =>  'medium',
                'action'        =>  '',
            ),
            1 =>    array(
                'targetE'       =>  'edit-mo-saml-custom-certificate',
                'pointToSide'   =>  'left',
                'titleHTML'     =>  t('<h1>Custom Certificate</h1>'),
                'contentHTML'   =>  t('You can add or generate your own public certificate and private key here.'),
                'ifNext'        =>  true,
                'buttonText'    =>  'End Tour',
                'cardSize'      =>  'big',
                'action'        =>  '',
                'ifskip'        =>  'hidden',
            ),
        );

        return isset($tourData[$pageID]) ? $tourData[$pageID] : '';
    }
}

/*
                            ********************************
                                    array terms :
                            ********************************
pageID              -   your Page ID, contains array of popups
0                   -   Popup/card number, goes from zero to n. For next Tab card use 'nextCard' instead of number
targetE             -   Element to target to. Has to be element ID without #. If no ID, add one. Empty For none, shows in centre of screen if empty
pointToSide         -   Direction of arrow to point to (up,down,left,right), for no arrow-keep empty (places at center keep targetE empty) //look at this fix
titleHTML           -   Title of card, can be HTML code
contentHTML         -   Content of card, can be HTML code
ifNext              -   if to show(true) Next Button or not(false), Keep False for Card Number('nextTab')
buttonText          -   Next Button Text
img                 -   image(icon) attributes ('src' should not be 'empty' with 'visible' true)
                        src     -   url of image(best for ico/transparent png) icon(https://visualpharm.com/assets/262/Comments-595b40b65ba036ed117d3e48.svg)
                        visible -   to show image or not, true or false
cardSize            -   Card has 3 difined sizes- big, medium and small. Recomended not to use image with small
nextTab             -   This is special card used if you want user to move to next tab during tour, disabled during restart tour

 */