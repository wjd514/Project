<?php
	function getThumb($o_path, $n_path, $width, $height){
		$o = array();
		$t = array();
	 
		// 원본 이미지 path 확인
		if(!file_exists($o_path))		return array('bool' => false);
	 
		// 원본 이미지 정보 호출
		$imginfo = getimagesize($o_path);
	 
		// 원본 이미지 mime 타입
		$o['mime'] = $imginfo['mime'];
	 
		// 원본 이미지 리소스 호출
		switch($o['mime']){
			case 'image/jpeg' :	$o['img'] = imagecreatefromjpeg($o_path);	break;
			case 'image/gif' :	$o['img'] = imagecreatefromgif($o_path);		break;
			case 'image/png' :	$o['img'] = imagecreatefrompng($o_path);	break;
			case 'image/bmp' :	$o['img'] = imagecreatefrombmp($o_path);	break;
			// mime 타입이 해당되지 않으면 return false
			default :		return array('bool' => false);						break;
		}
	 
		// 원본 이미지 크기
		$o['size'] = array('w' => $imginfo[0], 'h' => $imginfo[1]);
	 
		// 썸네일 이미지 가로, 세로 비율 계산
		$t['ratio']['w'] = $o['size']['w'] / $width;
		$t['ratio']['h'] = $o['size']['h'] / $height;
	 
		// 썸네일 이미지의 비율계산 (가로 == 세로)
		if($t['ratio']['w'] == $t['ratio']['h']){
			$t['size']['w'] = $width;
			$t['size']['h'] = $height;
		}
		// 썸네일 이미지의 비율계산 (가로 > 세로)
		elseif($t['ratio']['w'] > $t['ratio']['h']){
			$t['size']['w'] = $width;
			$t['size']['h'] = round(($width * $o['size']['h']) / $o['size']['w']);
		}
		// 썸네일 이미지의 비율계산 (가로 < 세로)
		elseif($t['ratio']['w'] < $t['ratio']['h']){
			$t['size']['w'] = round(($height * $o['size']['w']) / $o['size']['h']);
			$t['size']['h'] = $height;
		}
	 
		// 썸네일 이미지 리소스 생성
		$t['img'] = imagecreatetruecolor($t['size']['w'], $t['size']['h']);
	 
		// 썸네일 이미지 투명 배경 처리
		$bgclear = imagecolorallocate($t['img'],255,255,255);
		imagefill($t['img'],0,0,$bgclear);
	 
		// 원본 이미지 썸네일 이미지 크기에 맞게 복사
		ImageCopyResized($t['img'],$o['img'],0,0,0,0,$t['size']['w'],$t['size']['h'],$o['size']['w'],$o['size']['h']);
		ImageInterlace($t['img']);
	 
		// 썸네일 이미지 리소스를 기반으로 실제 이미지 생성
		switch($o['mime']){
			case 'image/jpeg' :	imagejpeg($t['img'], $n_path);	break;
			case 'image/gif' :	imagegif($t['img'], $n_path);	break;
			case 'image/png' :	imagepng($t['img'], $n_path);	break;
			case 'image/bmp' :	imagebmp($t['img'], $n_path);	break;
		}
	 
		// 원본 이미지 리소스 종료
		imagedestroy($o['img']);
		// 썸네일 이미지 리소스 종료
		imagedestroy($t['img']);
	 
		// 썸네일 파일경로 존재 여부 확인후 리턴
		return file_exists($n_path) ? array('bool' => true, 'path' => $n_path) : array('bool' => false);
	}
?>