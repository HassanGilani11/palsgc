<?php


namespace rednaowooextraproduct\core\Managers\FormManager\Fields;


use rednaowooextraproduct\core\Managers\FileManager\FileManager;
use rednaowooextraproduct\core\Managers\SlateGenerator\Core\HtmlTagWrapper;


class FBSignature extends FBFieldBase
{
    public function GetValue()
    {
        return $this->GetEntryValue('Value','');
    }

    public function GetText()
    {
        return $this->GetEntryValue('Value','');
    }

    public function GetLineItems(){
        if($this->Entry==null||$this->GetEntryValue('Value','')==='')
            return array();

        $fileManager=new FileManager($this->Loader);
        $this->Entry->Path=$fileManager->TemporaryUploadSignature($this->GetEntryValue('Value',''));

        return array((object)\array_merge((array)$this->Entry,array(
            'Id'=>$this->Options->Id,
            'FieldName'=>$this->Options->FieldName,
            'Type'=>$this->Options->Type
        )));
    }

    public function GetHtml($document)
    {
        $container=new HtmlTagWrapper($document,$document->createElement('img'));
        $path=$this->GetEntryValue('Path','');

        $prefix=$this->Loader->Prefix;
        $url=admin_url( 'admin-ajax.php').'?action='.$prefix.'_getpublicfileupload&path='.\basename($path).'&name='.'signature';

        $container->SetAttribute('src',$path);
        return $container;
    }

    public function CommitCreation()
    {
        $fileManager=new FileManager($this->Loader);
        $this->Entry->Path=$fileManager->GetOrderFolderRootPath().\basename($this->Entry->Path);
    }


}