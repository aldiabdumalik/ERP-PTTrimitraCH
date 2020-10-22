<?php

namespace App\Classes;

class ButtonBuilder
{
    /*
    |   BUTTON-PLACE
    |       - MAIN
    |       - MAIN-LINK
    |       - DATATABLE
    |       - DATATABLE-LINK
    |
    |   BUTTON-ACTION
    |       - ADD
    |       - EDIT
    |       - DELETE
    |       - TEMPLATE
    */
    public static function Build(
        $ButtonPlace, 
        $ButtonAction, 
        $ButtonID, 
        $ButtonIcon, 
        $ButtonPlaceholder, 
        $ButtonLink = '#', 
        $ButtonAdditionalAttr = null
    ){
        $Button;

        switch ($ButtonAction){
            case "ADD":
                $TypeClass = 'btn-primary';
            break;
            case "TEMPLATE":
                $TypeClass = 'btn-template';
            break;
            case "VIEW":
                $TypeClass = 'btn-warning view';
            break;
            case "EDIT":
                $TypeClass = 'btn-info edit';
            break;
            case "DELETE":
                $TypeClass = 'btn-danger delete';
            break;
            case "SAVE":
                $TypeClass = 'btn-success save';
            break;
            default:
                $TypeClass = 'btn-secondary';
        }

        switch ($ButtonPlace){
            case "MAIN":
                $Button = "<button class='btn btn-flat $TypeClass' id='$ButtonID' type='button' :additional_attribute: ><i class='$ButtonIcon'></i>&nbsp; $ButtonPlaceholder</button>";
            break;
            case "MAIN-LINK":
                $Button = "<a class='btn btn-flat $TypeClass' href='$ButtonLink' :additional_attribute: ><i class='$ButtonIcon'></i> &nbsp; $ButtonPlaceholder</a> &nbsp";
            break;
            case "DATATABLE":
                $Button = "<button class='btn btn-xs btn-flat $TypeClass' id='$ButtonID' type='button' :additional_attribute: ><i class='$ButtonIcon'></i>&nbsp; $ButtonPlaceholder</button> &nbsp";
            break;
            case "DATATABLE-LINK":
                $Button = "<a class='btn btn-flat btn-xs $TypeClass' href='$ButtonLink' :additional_attribute: ><i class='$ButtonIcon'></i> &nbsp; $ButtonPlaceholder</a> &nbsp";
            break;
            default:
                $Button = "<button class='btn btn-flat $TypeClass' id='$ButtonID' type='button' :additional_attribute: ><i class='$ButtonIcon'></i>&nbsp; $ButtonPlaceholder</button>";
        }

        $Button = str_replace(':additional_attribute:', $ButtonAdditionalAttr, $Button);

        return $Button;
    }

}