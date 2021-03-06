<?php

namespace App\Http;

use App\Http\BaseController;
use App\Traits\Databases;

class ImageController extends BaseController
{
    use Databases;

    public function __construct()
    {
        // Databases::init();

        $check = self::checkHeader();
        if ($check['state'] === false) {
            return BaseController::serverResponse($check, 403);
        }
    }

    public static function start()
    {
        $newfileBasename = BaseController::uuidSecure();

        $MediaName = BaseController::$MediaName;
        $MediaCateogry = BaseController::$MediaCategory;
        $MediaFile = BaseController::$MediaFile;
        $NeedThumbnail = BaseController::$NeedThumbnail;


        if (isset($MediaFile['error']) && $MediaFile['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $MediaFile['tmp_name'];
            $fileName = $MediaFile['name'];
            $fileSize = $MediaFile['size'];
            $fileType = $MediaFile['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $newFileName = $newfileBasename . '.' . $fileExtension;
            $newThumFileName = "thum_".$newFileName;
            $newSubDir = sha1(date("Ymd"));

            // $allowedFileTypes = array ( 'application/pdf', 'image/jpeg', 'image/png' );
            $allowedFileTypes = array ( 'image/jpeg', 'image/png', 'image/gif' );

            if (in_array($fileType, $allowedFileTypes)) {
                $baseDirectory = "/storage/{$MediaName}/{$MediaCateogry}/" . $newSubDir;
                $uploadFileDir = $_SERVER["DOCUMENT_ROOT"] . $baseDirectory;
                $uploadFileDestpath = $baseDirectory;
                $uploadFileURL = $baseDirectory;

                try {

                    if(!is_dir($uploadFileDir)) {
                        if(!mkdir($uploadFileDir, 0777, true)) {
                            BaseController::serverResponse([
                                'state' => false,
                                'message' => '처리중 문제가 발생 했습니다. (006)',
                            ], 500);
                        }
                    }

                    $dest_path = $uploadFileDir . "/" . $newFileName;
                    $dest_thum_path = $uploadFileDir . "/" . $newThumFileName;
                    $dest_url = $uploadFileURL . "/" . $newFileName;

                    if(move_uploaded_file($fileTmpPath, $dest_path)) {
                        list($fileWidth, $fileHeight) = getimagesize($dest_path);
                        if($NeedThumbnail === 'true') {
                            $resizeResult = BaseController::imageResize($dest_path, $dest_thum_path, 300, 300);
                            if(!$resizeResult['state']) {
                                BaseController::serverResponse([
                                    'state' => false,
                                    'message' => '처리중 문제가 발생 했습니다. (006)',
                                    'error' => '이미지 싸이즈 조정 실패 했습니다.'
                                ], 500);
                                return;
                            }
                        }

                        $uploadFileURL = PROTOCOL . $_SERVER["HTTP_HOST"] . $dest_url;
                        BaseController::serverResponse([
                            'state' => true,
                            'data' => [
                                'media_id' => date("YmdHis"),
                                'media_name' => $MediaName,
                                'media_category' => $MediaCateogry,
                                'media_full_url' => $uploadFileURL,
                                'dest_full_path' => $uploadFileDestpath.'/'.$newFileName,
                                'dest_path' => $uploadFileDestpath,
                                'new_file_name' => $newFileName,
                                'original_name' => $fileName,
                                'width' => $fileWidth,
                                'height' => $fileHeight,
                                'file_type' => $fileType,
                                'file_size' => $fileSize,
                                'file_extension' => $fileExtension,
                            ]
                        ], 201);
                        return;

                    } else {
                        BaseController::serverResponse([
                            'state' => false,
                            'message' => '처리중 문제가 발생 했습니다. (005)',
                            'error' => '이미지 이동중 문제가 발생 했습니다.'
                        ], 500);
                        return;
                    }

                } catch (\Exception $exception){
                    BaseController::serverResponse([
                        'state' => false,
                        'message' => '처리중 문제가 발생 했습니다. (003)',
                        'error' => $exception->getMessage()
                    ], 500);
                    return;
                }
            } else {
                BaseController::serverResponse([
                    'state' => false,
                    'message' => '처리중 문제가 발생 했습니다. (002)',
                    'error' => 'Upload failed. Allowed file types: ' . implode(',', $allowedfileExtensions)
                ], 400);
            }
        } else {
            BaseController::serverResponse([
                'state' => false,
                'message' => '처리중 문제가 발생 했습니다. (001)',
                'error' => $_FILES['image']['error']
            ], 400);
        }
    }
}
