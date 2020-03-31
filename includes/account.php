<?php
require_once('represent.php');
if (!class_exists('account', false)) {
    require 'classes/account.class.php';
}

$valueArr = '';
$account_id = null;
$account_group_id = null;

if (isset($_POST['userID'])) {
    $account_id = (int)$_POST['userID'];
}

if (isset($_POST['groupID'])) {
    $account_group_id = (int)$_POST['groupID'];
}

$account = new account($mysqli, $account_id, $account_group_id);
$requestResult = null;
$requestError = null;

switch ($method) {
    case 'migrateStatisticItem':
        /*
        echo "<Result>" . $account->migrateStatisticItem($_POST) . "</Result>";
        */
        $requestResult = ["Result" => $account->migrateStatisticItem($_POST)];
        break;
    case 'getStatistics':
        /*
        $output = "<Statistics>";
        if ($res = $account->getStatisticsA($_POST)) {
            $output = '<AssetCounts>';
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<AssetCount>";
                $output .= "<Date>" . $ds['asset_date'] . "</Date>";
                $output .= "<Timestamp>" . $ds['asset_timestamp'] . "</Timestamp>";
                $output .= "<FilmCount>" . $ds['filmCount'] . "</FilmCount>";
                $output .= "<ClipCount>" . $ds['clipCount'] . "</ClipCount>";
                $output .= "<MotifCount>" . $ds['motifCount'] . "</MotifCount>";
                $output .= "</AssetCount>";
            }
            $output .= "</AssetCounts>";
            echo $output;
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        }

        if ($res = $account->getStatisticsB($_POST)) {
            $output = '<ProductUsages>';
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<ProductUsage>";
                $output .= "<Name>" . $ds['name'] . "</Name>";
                $output .= "<Count>" . $ds['clipCount'] . "</Count>";
                $output .= "</ProductUsage>";
            }
            $output .= "</ProductUsages>";
            echo $output;
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        }

        if ($res = $account->getStatisticsC($_POST)) {
            $ds = mysqli_fetch_array($res);
            $output = "<TotalFilmCount>" . $ds['filmCount'] . "</TotalFilmCount>";
            $output .= "<TotalClipCount>" . $ds['clipCount'] . "</TotalClipCount>";
            echo $output;
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        }
        */

        $requestResult = ["Statistics" => []];

        if ($res = $account->getStatisticsA($_POST)) {
            $requestResult["Statistics"]["AssetCounts"] = [];
            while ($ds = mysqli_fetch_array($res)) {
                $requestResult["Statistics"]["AssetCounts"]["AssetCount"][] = [
                    "Date"       => $ds['asset_date'],
                    "Timestamp"  => $ds['asset_timestamp'],
                    "FilmCount"  => $ds['filmCount'],
                    "ClipCount"  => $ds['clipCount'],
                    "MotifCount" => $ds['motifCount'],
                ];
            }
        }

        if ($res = $account->getStatisticsB($_POST)) {
            $requestResult["Statistics"]["ProductUsages"] = [];
            while ($ds = mysqli_fetch_array($res)) {
                $requestResult["Statistics"]["ProductUsages"]["ProductUsage"][] = [
                    "Name"  => $ds['name'],
                    "Count" => $ds['clipCount'],
                ];
            }
        }

        if ($res = $account->getStatisticsC($_POST)) {
            $ds = mysqli_fetch_array($res);
            $requestResult["Statistics"]["TotalFilmCount"] = $ds['filmCount'];
            $requestResult["Statistics"]["TotalClipCount"] = $ds['clipCount'];
        }

        break;
    case 'login':
        /* POST-Vars:
         * string username
         * string password
         */
        /*
        if ($arr = $account->login($_POST)) {
            $output = '';
            $output .= "<Result>" . $arr['result'] . "</Result>";
            if (2 == $arr['result']) {
                $output .= "<ID>" . $arr['data']['account_id'] . "</ID>";
                $output .= "<Name1>" . $arr['data']['name'] . "</Name1>";
                $output .= "<Name2>" . $arr['data']['last_name'] . "</Name2>";
                $output .= "<Email>" . $arr['data']['email'] . "</Email>";
                $output .= "<AccountGroupID>" . $arr['data']['account_group_id'] . "</AccountGroupID>";
                $output .= "<CreationTime>" . strtotime($arr['data']['creation_time']) . "</CreationTime>";
                $output .= "<Phone>" . $arr['data']['phone'] . "</Phone>";
                $output .= "<CompanyName>" . $arr['data']['company'] . "</CompanyName>";
                $output .= "<Indicative>" . $arr['data']['indicative'] . "</Indicative>";
                $output .= "<Abdicative>" . $arr['data']['abdicative'] . "</Abdicative>";
                $output .= "<Quota>" . $arr['data']['quota'] . "</Quota>";
                $output .= "<Budget>" . $arr['data']['budget'] . "</Budget>";
                $output .= "<LanguageCode>" . $arr['data']['language_iso_code'] . "</LanguageCode>";
                $output .= "<Description>" . $arr['data']['description'] . "</Description>";
                $output .= "<LastLogin>" . strtotime($arr['data']['last_login']) . "</LastLogin>";
                $output .= "<LanguageIsoCode>" . $arr['data']['language_iso_code'] . "</LanguageIsoCode>";
                $output .= "<IsOnline>" . $arr['data']['is_online'] . "</IsOnline>";
            }
            echo $output;
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */

        if ($arr = $account->login($_POST)) {
            $requestResult = [];
            if (isset($arr['result']) && 2 == $arr['result'] && isset($arr['data'])) {
                $requestResult = [
                    "ID"              => $arr['data']['account_id'],
                    "Name1"           => $arr['data']['name'],
                    "Name2"           => $arr['data']['last_name'],
                    "Email"           => $arr['data']['email'],
                    "AccountGroupID"  => $arr['data']['account_group_id'],
                    "CreationTime"    => $arr['data']['creation_time'],
                    "Phone"           => $arr['data']['phone'],
                    "CompanyName"     => $arr['data']['company'],
                    "Indicative"      => $arr['data']['indicative'],
                    "Abdicative"      => $arr['data']['abdicative'],
                    "Quota"           => $arr['data']['quota'],
                    "Budget"          => $arr['data']['budget'],
                    "multilogin"      => $arr['data']['multilogin'],
                    "LanguageCode"    => $arr['data']['language_iso_code'],
                    "Description"     => $arr['data']['description'],
                    "LastLogin"       => $arr['data']['last_login'],
                    "LanguageIsoCode" => $arr['data']['language_iso_code'],
                    "IsOnline"        => $arr['data']['is_online'],
                    "onlinethreesixty"=> $arr['data']['onlinethreesixty'],
                ];
            }
            $requestResult["Result"] = $arr['result'];
        }

        break;
    case 'logout':
        /* POST-Vars:
         * int userID
         */
        /*
        if ($account->logout($_POST)) {
            echo '<ID>' . $_POST['accountID'] . '</ID>';
            unset($_SESSION);
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */
        if ($account->logout($_POST)) {
            $requestResult = ["ID" => $_POST['accountID']];
            unset($_SESSION);
        }

        break;
    case 'createAccount':
        /* POST-Vars:
         * int groupID
         * string language_iso_code
         * string username
         * string password
         * string fname
         * string lname
         * string email
         * string phone
         * string company
         * string description
         * string mail_txt_delete_film
         * string mail_txt_film_ready
         * int days_keep_rendered_film
         */
        /*
        if ($account->createAccount($_POST)) {
            echo '<ID>' . $account->_account_id . '</ID>';
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */
        if ($account->createAccount($_POST)) {
            $requestResult = ["ID" => $account->_account_id];
        }

        break;
    case 'updateAccount':
        /* POST-Vars:
         * int userID
         * int groupID
         * string language_iso_code
         * string username
         * string password => TODO: prüfen, ob hierüber auch PW neu gesetzt werden soll
         * string fname
         * string lname
         * string email
         * string phone
         * string company
         * string description
         * int is_active
         * string mail_txt_delete_film
         * string mail_txt_film_ready
         * int days_keep_rendered_film
         */
        /*
        if ($account->updateAccount($_POST)) {
            echo '<ID>' . $account->_account_id . ' updated</ID>';
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */

        if ($account->updateAccount($_POST)) {
            $requestResult = ["ID" => $account->_account_id . ' updated'];
        }

        break;
    case 'delteAccount':
        /* POST-Vars:
         * int userID
         */
        /*
        if ($account->deleteAccount($_POST)) {
            echo '<ID>' . $account->_account_id . ' deleted</ID>';
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */

        if ($account->deleteAccount($_POST)) {
            $requestResult = ["ID" => $account->_account_id . ' deleted'];
        }

        break;
    case 'workOnAccountGroup':
        switch ($_POST['task']) {
            case 'createAccountGroup':
                /*
                if ($account->createAccountGroup($_POST)) {
                    echo '<ID>' . $account->_account_group_id . '</ID>';
                } else {
                    echo '<ERROR>' . $account->_error . '</ERROR>';
                };
                */
                if ($account->createAccountGroup($_POST)) {
                    $requestResult = ["ID" => $account->_account_group_id];
                }

                break;
            case 'updateAccountGroup':
                /*
                if ($account->updateAccountGroup($_POST)) {
                    echo '<ID>' . $account->_account_group_id . ' updated</ID>';
                } else {
                    echo '<ERROR>' . $account->_error . '</ERROR>';
                };
                */
                if ($account->updateAccountGroup($_POST)) {
                    $requestResult = ["ID" => $account->_account_group_id . ' updated'];
                }

                break;
        }
        break;
    case 'workOnAccount':
        switch ($_POST['task']) {
            case 'createAccount':
                /*
                if ($account->createAccount($_POST)) {
                    echo '<ID>' . $account->_account_id . '</ID>';
                } else {
                    echo '<ERROR>' . $account->_error . '</ERROR>';
                };
                */
                if ($account->createAccount($_POST)) {
                    $requestResult = ["ID" => $account->_account_id];
                }
                break;
            case 'updateAccount':
                /*
                if ($account->updateAccount($_POST)) {
                    echo '<ID>' . $account->_account_id . ' updated</ID>';
                } else {
                    echo '<ERROR>' . $account->_error . '</ERROR>';
                };
                */
                if ($account->updateAccount($_POST)) {
                    $requestResult = ["ID" => $account->_account_id . ' updated'];
                }
                break;
        }
        break;
    case 'createAccountGroup':
        /* POST-Vars:
         * string name
         * string description
         * int indicative
         * int abdicative
         */
        /*
        if ($account->createAccountGroup($_POST)) {
            echo '<ID>' . $account->_account_group_id . '</ID>';
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */
        if ($account->createAccountGroup($_POST)) {
            $requestResult = ["ID" => $account->_account_group_id];
        }

        break;
    case 'updateAccountGroup':
        /* POST-Vars:
         * int groupID
         * string name
         * string description
         * int indicative
         * int abdicative
         */
        /*
        if ($account->updateAccountGroup($_POST)) {
            echo '<ID>' . $account->_account_group_id . ' updated</ID>';
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */

        if ($account->updateAccountGroup($_POST)) {
            $requestResult = [$account->_account_group_id . ' updated'];
        }

        break;
    case 'deleteAccountGroup':
        /* POST-Vars:
         * int groupID
         */
        /*
        if ($account->deleteAccountGroup($_POST)) {
            echo '<ID>' . $account->_account_group_id . ' deleted</ID>';
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */
        if ($account->deleteAccountGroup($_POST)) {
            $requestResult = [$account->_account_group_id . ' deleted'];
        }

        break;
    case 'getProductAssociations':
        /*
        if ($res = $account->getProductAssociations($_POST)) {
            $output = '';
            $output .= "<ProductIDs>";
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<ProductID>" . $ds['product_id'] . "</ProductID>";
            }//while
            $output .= "</ProductIDs>";
            echo $output;
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */
        if ($res = $account->getProductAssociations($_POST)) {
            $requestResult = ["ProductIDs" => ["ProductID" => []]];
            while ($ds = mysqli_fetch_array($res)) {
                $requestResult["ProductIDs"]["ProductID"][] = $ds['product_id'];
            }
        }
        break;
    case 'getGroupAssociations':
        /*
        if ($res = $account->getGroupAssociations($_POST)) {
            $output = '';
            $output .= "<AccountGroupIDs>";
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<AccountGroupID>" . $ds['account_group_id'] . "</AccountGroupID>";
            }//while
            $output .= "</AccountGroupIDs>";
            echo $output;
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */

        if ($res = $account->getGroupAssociations($_POST)) {
            $requestResult = ["AccountGroupIDs" => ["AccountGroupID" => []]];
            while ($ds = mysqli_fetch_array($res)) {
                $requestResult["AccountGroupIDs"]["AccountGroupID"][] = $ds['account_group_id'];
            }
        }

        break;
    case 'getAccountGroupList':
        if ($res = $account->getAccountGroupList()) {
            $requestResult = ["AccountGroups" => ["AccountGroup" => []]];
            while ($ds = mysqli_fetch_array($res)) {
                $requestResult["AccountGroups"]["AccountGroup"][] = [
                    "ID" => $ds['account_group_id'],
                    "Name" => $ds['name'],
                    "Indicative" => $ds['indicative'],
                    "Abdicative" => $ds['abdicative'],
                    "Description" => $ds['description'],
                    "onlinethreesixty" => $ds['onlinethreesixty'],
                    "BillingType" => $ds['billing_type'],
                    "Credits" => $ds['credits']
                ];
            }
        }

        break;
    case 'getAccountList':
        /*
        if ($res = $account->getAccountList()) {
            $output = '';
            $output .= "<Accounts>";
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<Account>";
                $output .= "<Id>" . $ds['account_id'] . "</Id>";
                $output .= "<AccountGroupID>" . $ds['account_group_id'] . "</AccountGroupID>";
                $output .= "<LanguageIsoCode>" . $ds['language_iso_code'] . "</LanguageIsoCode>";
                $output .= "<LoginUsername>" . $ds['login_username'] . "</LoginUsername>";
                $output .= "<Name>" . $ds['name'] . "</Name>";
                $output .= "<LastName>" . $ds['last_name'] . "</LastName>";
                $output .= "<Email>" . $ds['email'] . "</Email>";
                $output .= "<Phone>" . $ds['phone'] . "</Phone>";
                $output .= "<Comany>" . $ds['company'] . "</Comany>";
                $output .= "<CreationTime>" . strtotime($ds['creation_time']) . "</CreationTime>";
                $output .= "<Description>" . $ds['description'] . "</Description>";
                $output .= "<IsOnline>" . $ds['is_online'] . "</IsOnline>";
                $output .= "<IsActive>" . $ds['is_active'] . "</IsActive>";
                $output .= "<LastLogin>" . strtotime($ds['last_login']) . "</LastLogin>";
                $output .= "<CurrentIPPart>" . $ds['current_ip_part'] . "</CurrentIPPart>";
                $output .= "<MailTextDeleteFilm>" . $ds['mail_txt_delete_film'] . "</MailTextDeleteFilm>";
                $output .= "<MailTextFilmReady>" . $ds['mail_txt_film_ready'] . "</MailTextFilmReady>";
                $output .= "<DaysKeepRenderedFilm>" . $ds['days_keep_rendered_film'] . "</DaysKeepRenderedFilm>";
                $output .= "<Quota>" . $ds['quota'] . "</Quota>";
                $output .= "<Budget>" . $ds['budget'] . "</Budget>";
                $output .= "</Account>";
            }//while
            $output .= "</Accounts>";
            echo $output;
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */

        if ($res = $account->getAccountList()) {
            $requestResult = ["Accounts" => ["Account" => []]];
            while ($ds = mysqli_fetch_array($res)) {
                $requestResult["Accounts"]["Account"][] = [
                    "Id"                   => $ds['account_id'],
                    "AccountGroupID"       => $ds['account_group_id'],
                    "LanguageIsoCode"      => $ds['language_iso_code'],
                    "LoginUsername"        => $ds['login_username'],
                    "Name"                 => $ds['name'],
                    "LastName"             => $ds['last_name'],
                    "Email"                => $ds['email'],
                    "Phone"                => $ds['phone'],
                    "Comany"               => $ds['company'],
                    "CreationTime"         => strtotime($ds['creation_time']),
                    "Description"          => $ds['description'],
                    "IsOnline"             => $ds['is_online'],
                    "IsActive"             => $ds['is_active'],
                    "LastLogin"            => strtotime($ds['last_login']),
                    "CurrentIPPart"        => $ds['current_ip_part'],
                    "MailTextDeleteFilm"   => $ds['mail_txt_delete_film'],
                    "MailTextFilmReady"    => $ds['mail_txt_film_ready'],
                    "DaysKeepRenderedFilm" => $ds['days_keep_rendered_film'],
                    "Quota"                => $ds['quota'],
                    "Budget"               => $ds['budget'],
                    "multilogin"           => $ds['multilogin'],
                ];
            }
        }
        break;
    case 'getFileExtensions':
        /*
        if ($res = $account->getFileExtensions()) {
            $output .= "<MediaFormats>";
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<MediaFormat>";
                $output .= "<ID>" . $ds['media_format_id'] . "</ID>";
                $output .= "<Extension>" . $ds['extension'] . "</Extension>";
                $output .= "<Name>" . $ds['name'] . "</Name>";
                $output .= "<Type>" . $ds['format_type'] . "</Type>";
                $output .= "</MediaFormat>";
            }//while
            $output .= "</MediaFormats>";
            echo $output;
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */

        if ($res = $account->getFileExtensions()) {
            $requestResult = ['MediaFormats' => ['MediaFormat' => []]];
            while ($ds = mysqli_fetch_array($res)) {
                $requestResult['MediaFormats']['MediaFormat'][] = [
                    "ID"        => $ds['media_format_id'],
                    "Extension" => $ds['extension'],
                    "Name"      => $ds['name'],
                    "Type"      => $ds['format_type'],
                ];
            }
        }

        break;
    case 'getContentFormats':
        /*
        if ($res = $account->getContentFormats()) {
            $output .= "<ContentFormats>";
            while ($ds = mysqli_fetch_array($res)) {
                $output .= "<ContentFormat>";
                $output .= "<ID>" . $ds['content_format_id'] . "</ID>";
                $output .= "<Aspect>" . $ds['aspect'] . "</Aspect>";
                $output .= "<Name>" . $ds['name'] . "</Name>";
                $output .= "</ContentFormat>";
            }//while
            $output .= "</ContentFormats>";
            echo $output;
        } else {
            echo '<ERROR>' . $account->_error . '</ERROR>';
        };
        */

        if ($res = $account->getContentFormats()) {
            $requestResult = ["ContentFormats" => ["ContentFormat" => []]];
            while ($ds = mysqli_fetch_array($res)) {
                $requestResult["ContentFormats"]["ContentFormat"][] = [
                    "ID"     => $ds['content_format_id'],
                    "Aspect" => $ds['aspect'],
                    "Name"   => $ds['name'],
                    "width"  => $ds['width'],
                    "height" => $ds['height'],
                ];
            }
        }

        break;
    case 'exportStatistics':
        $account->exportStatistics($_POST);
        /*if ($res = $account->exportStatistics($_POST))
            echo "<ExportResult><Success>1</Success></ExportResult>";
        else
            echo "<ExportResult><Success>0</Success></ExportResult>";
            */
        break;
    case 'adjustNamedObject':
        /*
        if ($res = $account->adjustNamedObject($_POST)) {
            echo "<Name>" . $res . "</Name>";
        } else {
            echo "<Name>0</Name>";
        }
        */
        if ($res = $account->adjustNamedObject($_POST)) {
            $requestResult = ["Name" => $res];
        }
        break;
    case "setMaintain":
        $result = (bool)$account->setMaintain($_POST);
        if ($result) {
            $requestResult = ["Result" => $result];
        }
        break;
    default:
        break;
}

$requestError = $account->_error;
echo (new Represent($requestResult, $requestError))->getJson();