<?php
class DocumentBehavior extends CActiveRecordBehavior
{
	/*
	public function afterFind() {
        parent::afterFind();
        $this->oldfile = $this->file;
    }
 
    public function afterDelete() {
        $this->deleteFile();
        return parent::afterDelete();
    }*/
	
	public function beforeValidate($event)
	{
		$this->owner->creation_date = date('Y-m-d');
		$this->owner->user_id = Yii::app()->user->id;
		
		return parent::beforeValidate();
	}
 
    public function beforeSave() 
    {
    	$this->owner->path = "/../assets/media/".$this->owner->file;
    	$uploadPath = Yii::app()->getBasePath() . $this->owner->path;
    	
        if(is_object($this->owner->file)) {
            $this->owner->file->saveAs($uploadPath);
            
            /*if(!empty($this->oldfile)) {
                $delete = Yii::app()->params['uploadPath'].'/'.$this->oldfile;
                if(file_exists($delete)) unlink($delete);
            }*/
            return parent::beforeSave();
        }
        //if(empty($this->file) && !empty($this->oldfile)) $this->file = $this->oldfile;
        //return parent::beforeSave();
        return false;
    }
 
    /*public function deleteFile() {
        $imagem = $this->file;
        return unlink(Yii::app()->params['uploadPath'].'/'.$imagem);
    }
    */
}