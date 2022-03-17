<?
namespace ImageConverter;
class Picture {

	private static $isPng = true;

	private static function checkFormat($str)
	{
		if ($str === 'image/png')
		{
			self::$isPng = true;

			return true;
		}
		elseif ($str === 'image/jpeg')
		{
			self::$isPng = false;

			return true;
		}
		else return false;
	}

	private static function implodeSrc($arr)
	{
		$arr[count($arr) - 1] = '';

		return implode('/', $arr);
	}

	private static function generateSrc($str)
	{
		$arPath = explode('/', $str);

		if ($arPath[2] === 'resize_cache')
		{
			$arPath = self::implodeSrc($arPath);

			return str_replace('resize_cache/iblock', 'webp/resize_cache', $arPath);
		}
		else
		{
			$arPath = self::implodeSrc($arPath);

			return str_replace('upload/iblock', 'upload/webp/iblock', $arPath);
		}
	}

    public static function getWebp($array, $intQuality = 70)
	{
		if (self::checkFormat($array['CONTENT_TYPE']))
		{
			$array['WEBP_PATH'] = self::generateSrc($array['SRC']);

			if (self::$isPng)
			{
				$array['WEBP_FILE_NAME'] = str_replace('.png', '.webp', strtolower($array['FILE_NAME']));
			}
			else
			{
				$array['WEBP_FILE_NAME'] = str_replace('.jpg', '.webp', strtolower($array['FILE_NAME']));
				$array['WEBP_FILE_NAME'] = str_replace('.jpeg', '.webp', strtolower($array['WEBP_FILE_NAME']));
			}

			if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $array['WEBP_PATH']))
			{
				mkdir($_SERVER['DOCUMENT_ROOT'] . $array['WEBP_PATH'], 0777, true);
			}

			$array['WEBP_SRC'] = $array['WEBP_PATH'] . $array['WEBP_FILE_NAME'];

			if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $array['WEBP_SRC']))
			{
				if (self::$isPng)
				{
					$im = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . $array['SRC']);
				}
				else
				{
					$im = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT'] . $array['SRC']);
				}

				imagewebp($im, $_SERVER['DOCUMENT_ROOT'] . $array['WEBP_SRC'], $intQuality);

				imagedestroy($im);

				if (filesize($_SERVER['DOCUMENT_ROOT'] . $array['WEBP_SRC']) % 2 == 1)
				{
					file_put_contents($_SERVER['DOCUMENT_ROOT'] . $array['WEBP_SRC'], "\0", FILE_APPEND);
				}
			}
		}

		return $array;
    }

	public static function resizePict($file, $width, $height, $isProportional = true, $intQuality = 70)
	{
		$file = \CFile::ResizeImageGet($file, array('width'=>$width, 'height'=>$height), ($isProportional ? BX_RESIZE_IMAGE_PROPORTIONAL : BX_RESIZE_IMAGE_EXACT), false, false, false, $intQuality);

		return $file['src'];
	}

	public static function getResizeWebp($file, $width, $height, $isProportional = true, $intQuality = 70)
	{
		$file['SRC'] = self::resizePict($file, $width, $height, $isProportional, $intQuality);

		$file = self::getWebp($file, $intQuality);

		return $file;
	}

	public static function getResizeWebpSrc($file, $width, $height, $isProportional = true, $intQuality = 70)
	{
		$file['SRC'] = self::resizePict($file, $width, $height, $isProportional, $intQuality);

		$file = self::getWebp($file, $intQuality);

		return $file['WEBP_SRC'];
	}
	
	public static function getResizeWebpFileId($fileId, $width, $height, $isProportional = true, $intQuality = 70)
	{
		$file = \CFile::GetFileArray($fileId);
		$file['SRC'] = self::resizePict($file, $width, $height, $isProportional, $intQuality);
		$file = self::getWebp($file, $intQuality);

		return $file;
	}
}