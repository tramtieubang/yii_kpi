<?php

namespace app\custom;

use app\modules\vanban\models\VanBanDen;
use app\modules\vanban\models\VanBanDi;
use app\modules\hocvien\models\HocVien;
use app\modules\giaovien\models\GiaoVien;
use app\modules\nhanvien\models\NhanVien;
use app\modules\khoahoc\models\KhoaHoc;

class DoiTuong
{ 
    public static function getModel($doiTuong, $idDoiTuong){
        $model = NULL;
        if($doiTuong == VanBanDen::MODEL_ID){
            $model = VanBanDen::findOne($idDoiTuong);
        } else if($doiTuong == VanBanDi::MODEL_ID){
            $model = VanBanDi::findOne($idDoiTuong);
        } else if($doiTuong == HocVien::MODEL_ID){
            $model = HocVien::findOne($idDoiTuong);
        }else if($doiTuong == GiaoVien::MODEL_ID){
            $model = GiaoVien::findOne($idDoiTuong);
        }else if($doiTuong == NhanVien::MODEL_ID){
            $model = NhanVien::findOne($idDoiTuong);
        }else if($doiTuong == Khoahoc::MODEL_ID){
            $model = KhoaHoc::findOne($idDoiTuong);
        }
        return $model;
    }
    
}