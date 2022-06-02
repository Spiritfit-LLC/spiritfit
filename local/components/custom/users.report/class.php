<?php


class UsersReportComponent extends CBitrixComponent{
    function executeComponent()
    {
        $this->arResult=array();

        //Количество КЛИЕНТОВ
        $CLIENTS=[];
        $client_count=0;

        $order = array('sort' => 'asc');
        $tmp = 'sort'; // параметр проигнорируется методом, но обязан быть
        $rsUsers = CUser::GetList($order, $tmp, array('GROUPS_ID'=>Utils::GetUGroupIDBySID('CLIENTS')), array('SELECT'=>["UF_*"]));
        while($arUser=$rsUsers->GetNext()){
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
        $PHCK=[];
        $client_count=0;

        $order = array('sort' => 'asc');
        $tmp = 'sort'; // параметр проигнорируется методом, но обязан быть
        $rsUsers = CUser::GetList($order, $tmp, array('GROUPS_ID'=>Utils::GetUGroupIDBySID('POTENTIAL_CLIENTS')));
        while($rsUsers->GetNext()){
            $client_count++;
        }
        $this->arResult['PCHK_COUNT']=$client_count;


       $this->IncludeComponentTemplate();
    }
}
