<?php


class UsersReportComponent extends CBitrixComponent{
    function executeComponent()
    {
        $this->arResult=array();

        //Количество КЛИЕНТОВ
        $CLIENTS=[];
        $client_count=0;

        $order = array('sort' => 'asc');
        $tmp = 'sort';
        $rsUsers = CUser::GetList($order, $tmp, array('GROUPS_ID'=>Utils::GetUGroupIDBySID('CLIENTS')), array('SELECT'=>["UF_*"]));
        while($arUser=$rsUsers->GetNext()){
            if (empty($arUser['UF_LEVEL']) || $arUser['UF_LEVEL']==''){
                $arUser['UF_LEVEL']='Без уровня';
            }
            $CLIENTS[$arUser['UF_LEVEL']][]=$arUser;
            $client_count++;
        }

        $this->arResult['CLIENT_COUNT']=$client_count;
        foreach($CLIENTS as $level=>$value){

            $this->arResult['LEVELS'][]=[
                'NAME'=>$level,
                'COUNT'=>count($CLIENTS[$level])
            ];
        }


        //ПЧК
        $client_count=0;

        $order = array('sort' => 'asc');
        $tmp = 'sort';
        $rsUsers = CUser::GetList($order, $tmp, array('GROUPS_ID'=>Utils::GetUGroupIDBySID('POTENTIAL_CLIENTS')));
        while($rsUsers->GetNext()){
            $client_count++;
        }
        $this->arResult['PCHK_COUNT']=$client_count;


       $this->IncludeComponentTemplate();
    }
}
