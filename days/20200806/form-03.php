<?php
/**
 * +------------------------------------------------------
 * 大文件分片上传
 * +------------------------------------------------------
 * @category        控制器类。
 * @author          刘亚杰
 * @lastEditTime    2020-08-06
 * +------------------------------------------------------
 * @描述：大文件分片上传
 * +------------------------------------------------------
 */
//header('Access-Control-Allow-Credentials: false');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header('Access-Control-Allow-Headers: *');
header('Access-Control-Max-Age: 1000');

class UploadLargeFile
{
    // 文件基础目录
    private $baseDir;
    // 分片文件临时存放目录,在基础目录下边
    private $fileDir;
    // PHP文件临时地址
    private $tmpPath;
    // 第几个文件块
    private $blobNum;
    // 文件块总数
    private $totalBlobNum;
    // 文件名
    private $fileName;
    // 新文件的绝对地址
    private $newFile;
    // 新文件的访问地址
    private $accessAddr;

    public function __construct($file, $data)
    {
        $this->baseDir      = 'C:/www/php' . '/upload';
        $this->tmpPath      = $file['tmp_name'];
        $this->fileDir      = $this->baseDir . '/' . date('YmdHis',strtotime($data['lastModifiedDate']));
        $this->blobNum      = $data['chunk']+1;
        $this->totalBlobNum = $data['chunks'];
        $this->fileName     = $data['name'];
        $this->touchDir();
        $this->moveFile();
        $this->fileMerge();
    }

    // 建立上传文件夹
    private function touchDir()
    {
        // 基础文件目录
        if (!file_exists($this->baseDir)) {
            mkdir($this->baseDir);
        }
        // 临时放置文件的目录
        if (!file_exists($this->fileDir)) {
            mkdir($this->fileDir);
        }
    }

    // 移动temp文件到临时目录中
    private function moveFile()
    {
        $filename = $this->fileDir . '/' . $this->fileName . '__' . $this->blobNum;
        move_uploaded_file($this->tmpPath, $filename);
    }

    //判断是否是最后一块，如果是则进行文件合成并且删除文件块
    public function fileMerge()
    {
        if ($this->blobNum == $this->totalBlobNum) {
            $time = date('YmdHis');
            $this->newFile = $this->baseDir . '/' . $time . $this->fileName;
            $dh   = fopen($this->newFile, 'a+');
            for ($i = 1; $i <= $this->totalBlobNum; $i++) {
                $blob = file_get_contents($this->fileDir . '/' . $this->fileName . '__' . $i);
                fwrite($dh, $blob);
            }
            fclose($dh);
            $this->accessAddr = 'http://' . $_SERVER['HTTP_HOST'] . '/upload/' . $time . $this->fileName;
            $this->deleteFileBlob();
        }
    }

    //删除文件块
    private function deleteFileBlob()
    {
        for ($i = 1; $i <= $this->totalBlobNum; $i++) {
            @unlink($this->fileDir . '/' . $this->fileName . '__' . $i);
        }
        @rmdir($this->fileDir);
    }

    // API返回数据
    public function apiReturn()
    {
        $data = [];
        if ($this->blobNum == $this->totalBlobNum) {
            if (file_exists($this->newFile)) {
                $data['code']       = 1;
                $data['msg']        = 'success';
                $data['file_path']    = $this->newFile;
                $data['accessAddr'] = $this->accessAddr;
            }
        } else {
            if (file_exists($this->fileDir . '/' . $this->fileName . '__' . $this->blobNum)) {
                $data['code']      = 0;
                $data['msg']       = 'waiting for all';
                $data['file_path'] = '';
                $data['accessAddr'] = '';
            }
        }
        return $data;
    }
}
$upload = new UploadLargeFile($_FILES['file'],$_POST);
$data = $upload->apiReturn();
echo json_encode($data);