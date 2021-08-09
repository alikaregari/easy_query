<?php
/**
 * Created by PhpStorm.
 * User: Ali Kargari
 * Date: 12/28/2018
 * Time: 12:42 PM
 */
require_once "database.php";
class Query extends Database
{

    private $tbl;
    private $data_field;
    private $data_insert;
    public $user_id;

    protected function SetTbl($tbl)
    {
        $this-> tbl= $tbl;
    }
    protected function SelectData($data,$order,$orderby)
    {
        if(is_array($data))
        {
            $this->data_field=implode(",",$data);
        }
        else
        {
            $this->data_field=$data;
        }
        $sql=$this->pdo->prepare("SELECT {$this->data_field} FROM {$this->tbl} ORDER BY $order $orderby");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function SelectData_s($data)
    {
        if(is_array($data))
        {
            $this->data_field=implode(",",$data);
        }
        else
        {
            $this->data_field=$data;
        }
        $sql=$this->pdo->prepare("SELECT {$this->data_field} FROM {$this->tbl}");
        $sql->execute();
        $res=$sql->fetch(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function InsertData($data,$field)
    {
        if(is_array($field))
        {
            $this->data_field='`'.implode("`,`",$field).'`';
        }
        if(is_array($data))
        {
            $this->data_insert="'".implode("','",$data)."'";
        }
        $sql=$this->pdo->prepare("INSERT INTO {$this->tbl} ({$this->data_field}) VALUES ({$this->data_insert})");
        $sql->execute();
	    var_dump($sql->errorInfo());
    }
    protected function Img_Uploader($dir,$img,$file)
    {
        if(is_array($file))
        {
            $user=$file['email'];
        }
        else
        {
            $user=$file;
        }
        $folder="$dir-".$user;
        if(!file_exists($folder))
        {
            @mkdir("img/$dir/$folder");
        }
        $pic_name=$img['name'];
        $pic_explode=explode(".",$pic_name);
        $pic_end=end($pic_explode);
        $new_pic_name=rand().".$pic_end";
        $from=$img['tmp_name'];
        $to="img/$dir/$folder/$new_pic_name";
        move_uploaded_file($from,$to);
        return $to;
    }
    protected function video_Uploader($dir,$video,$file){
        $folder=$file;
        if(!file_exists($folder))
        {
            @mkdir("$dir/$folder");
        }
        $pic_name=$video['name'];
        $pic_explode=explode(".",$pic_name);
        $pic_end=end($pic_explode);
        $new_pic_name=$file.".".$pic_end;
        $from=$video['tmp_name'];
        $to="$dir/$folder/$new_pic_name";
        move_uploaded_file($from,$to);
        return $to;
    }
    protected function APK_Uploader($dir,$apk,$file)
    {
        $folder_ex=explode('.',$file);
        $folder='version-'.$folder_ex['0'];
        if(!file_exists($folder))
        {
            @mkdir("$dir/$folder");
        }
        $pic_name=$apk['name'];
        $pic_explode=explode('.',$pic_name);
        $str_c=count($pic_explode);
        unset($pic_explode[$str_c-1]);
        $newname=implode($pic_explode,'.');
        $new_name=$newname."_$file.apk";
        $from=$apk['tmp_name'];
        $to="$dir/$folder/$new_name";
        move_uploaded_file($from,$to);
        return $to;
    }
    protected function PDF_Uploader($dir,$pdf,$file){
        $folder=$file;
        if(!file_exists($folder))
        {
            @mkdir("pdf/$dir/$folder");
        }
        $pdf_name=$pdf['name'];
        $pdf_explode=explode(".",$pdf_name);
        $pdf_end=end($pdf_explode);
        $new_pdf_name=rand().".$pdf_end";
        $from=$pdf['tmp_name'];
        $to="pdf/$dir/$folder/$new_pdf_name";
        move_uploaded_file($from,$to);
        return $to;
    }
    protected function UpdateData($field,$data,$id)
    {
        $this->user_id=$id;
        if(is_array($field))
        {
            foreach ($field as $key=>$val)
            {
                $array[]='`'.$val."`='".$data[$val]."'";
            }
            $query=implode(",",$array);
            $sql=$this->pdo->prepare("UPDATE {$this->tbl} SET {$query} WHERE id='{$this->user_id}'");
            //var_dump($sql);
            $recap=$sql->execute();
            //return $recap;
        }
        else
        {
            $sql=$this->pdo->prepare("UPDATE {$this->tbl} SET $field='$data' WHERE id='{$this->user_id}'");
            //var_dump($sql);
            $sql->execute();
        }

    }
    protected function DeleteData($id)
    {
        $this->user_id=$id;
        $sql=$this->pdo->prepare("DELETE FROM {$this->tbl} WHERE id='{$this->user_id}'");
        $sql->execute();
    }
    protected function DeleteData_custom($key,$value)
    {
        $sql=$this->pdo->prepare("DELETE FROM {$this->tbl} WHERE $key='{$value}'");
        var_dump($sql);
        $sql->execute();
    }
    protected function Search($key,$value,$order,$orderby)
    {
        if($value==null):
            $sql=$this->pdo->prepare("SELECT * FROM {$this->tbl} WHERE $key IS NULL ORDER BY  $order $orderby");
        else:
            $sql=$this->pdo->prepare("SELECT * FROM {$this->tbl} WHERE $key='$value' ORDER BY  $order $orderby");
        endif;
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Search_And($key1,$key2,$value1,$value2)
    {
        $sql=$this->pdo->prepare("SELECT * FROM {$this->tbl} WHERE $key1='$value1' AND $key2='$value2'");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Search_And_Sort($key1,$key2,$value1,$value2,$order,$orderby)
    {
        $sql=$this->pdo->prepare("SELECT * FROM {$this->tbl} WHERE $key1='$value1' AND $key2='$value2' ORDER BY  $order $orderby");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Search_And_2_Sort($key1,$key2,$key3,$value1,$value2,$value3,$order,$orderby)
    {
        $sql=$this->pdo->prepare("SELECT * FROM {$this->tbl} WHERE $key1='$value1' AND $key2='$value2' AND $key3='$value3' ORDER BY  $order $orderby");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Search_s($key,$value)
    {
        $sql=$this->pdo->prepare("SELECT * FROM {$this->tbl} WHERE $key='$value'");
        //var_dump($sql);
        $sql->execute();
        $res=$sql->fetch(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Like($key,$value)
    {
        $sql=$this->pdo->prepare("SELECT * FROM {$this->tbl} WHERE $key LIKE '%$value%'");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Like_And($key1,$key2,$value1,$value2)
    {
        $sql=$this->pdo->prepare("SELECT * FROM {$this->tbl} WHERE $key1 LIKE '%$value1%' AND $key2='$value2'");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Min_Select($column_name,$key,$value){
        $sql=$this->pdo->prepare("SELECT * FROM {$this->tbl} WHERE $column_name=(SELECT MIN($column_name) FROM {$this->tbl} WHERE $key='$value')");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Max_Select($column_name,$key,$value){
        $sql=$this->pdo->prepare("SELECT * FROM {$this->tbl} WHERE $column_name=(SELECT MAX($column_name) FROM {$this->tbl} WHERE $key='$value')");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Sum_And($column_name,$key1,$key2,$value1,$value2){
        $sql=$this->pdo->prepare("SELECT SUM($column_name) FROM {$this->tbl} WHERE $key1='$value1' AND $key2='$value2'");
        $sql->execute();
        $res=$sql->fetch(\PDO::FETCH_ASSOC);
        return $res;
    }
    protected function Limit($data,$limit){
        if(is_array($data))
        {
            $this->data_field=implode(",",$data);
        }
        else
        {
            $this->data_field=$data;
        }
        $sql=$this->pdo->prepare("SELECT {$this->data_field} FROM {$this->tbl} ORDER BY id DESC LIMIT $limit");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Group($key,$value,$group){
        $sql=$this->pdo->prepare("SELECT * FROM {$this->tbl} WHERE $key='$value' GROUP BY $group");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Remove_Picture($data,$field_name){
        //پاک کردن آدرس و فولدر عکس مورد نظر
        $explode=explode('/',$data["$field_name"]);
        $end=count($explode)-1;
        unset($explode["$end"]);
        $folder=implode('/',$explode);
        unlink($data["$field_name"]);
        rmdir($folder);
    }
	protected function Page_Navi($data,$order,$orderby,$page_item,$start)
	{
		if(is_array($data))
		{
			$this->data_field=implode(",",$data);
		}
		else
		{
			$this->data_field=$data;
		}
		$sql=$this->pdo->prepare("SELECT {$this->data_field} FROM {$this->tbl} ORDER BY $order $orderby LIMIT $start, $page_item");
		$sql->execute();
		$res=$sql->fetchAll(\PDO::FETCH_OBJ);
		return $res;
	}
    protected function Page_Navi_Search($data,$key,$value,$operations,$order,$orderby,$page_item,$start)
    {
        if(is_array($data))
        {
            $this->data_field=implode(",",$data);
        }
        else
        {
            $this->data_field=$data;
        }
        $sql=$this->pdo->prepare("SELECT {$this->data_field} FROM {$this->tbl} WHERE $key$operations'$value' ORDER BY $order $orderby LIMIT $start, $page_item");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Page_Navi_Search_And($data,$key,$value,$operations,$key2,$value2,$operations2,$order,$orderby,$page_item,$start)
    {
        if(is_array($data))
        {
            $this->data_field=implode(",",$data);
        }
        else
        {
            $this->data_field=$data;
        }
        $sql=$this->pdo->prepare("SELECT {$this->data_field} FROM {$this->tbl} WHERE $key$operations'$value' AND $key2$operations2'$value2'  ORDER BY $order $orderby LIMIT $start, $page_item");
        $sql->execute();
        $res=$sql->fetchAll(\PDO::FETCH_OBJ);
        return $res;
    }
    protected function Last_Record($data,$order){
	    $sql=$this->pdo->prepare(" SELECT $data FROM {$this->tbl} ORDER BY $order DESC LIMIT 1");
	    $sql->execute();
	    $res=$sql->fetch(\PDO::FETCH_OBJ);
	    return $res;
    }
}
