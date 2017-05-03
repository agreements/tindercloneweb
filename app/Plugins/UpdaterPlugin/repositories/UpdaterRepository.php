<?php

namespace App\Plugins\UpdaterPlugin\Repositories;

use Storage;
use Artisan;

class UpdaterRepository {


	/*public function finishUpdate() {

		$this->deleteFile($this->localUpdateFilePath(true));

		$temp_updates_array = $this->tempUpdatesArray();
		$this->deleteFile($this->tempUpdateFilePath(true));
		$temp_updates_array = $temp_updates_array["updates"];
		$temp_updates_array["update_status"] = "success";
		$temp_updates_array["timestamp"] = date("Y-m-d H:i:s");
		$json_content = json_encode($temp_updates_array);

		Storage::put($this->localUpdateFilePath(true), $json_content);

		return true;
	}*/


	public function checkPermissionsForUpdateLoop($update_dir) {
		$update_meta = include app_path("Plugins/UpdaterPlugin/updates/{$update_dir}/update_meta.php");
		$rel_updates_dir = "app/Plugins/UpdaterPlugin/updates/{$update_dir}/update_files";

		foreach ($update_meta["update_files_meta"] as $file => $command_desc) {

			switch ($command_desc["file_type"]) {

				case 'file':
				case 'language':

					$base_path_to_file = base_path("{$file}");

					if ( file_exists($base_path_to_file) && !is_writable($base_path_to_file) ) {
						return base_path("{$file}");
					} 

					break;
				
				case 'directory':

					$base_path_to_file = base_path("{$file}");

					if ( file_exists(dirname($base_path_to_file)) && !is_writable( dirname($base_path_to_file) ) )
						return dirname($base_path_to_file);

					break;			
			}


		}

		return "";
	}


	



	public function updateLoop($update_dir) {
		$update_meta = include app_path("Plugins/UpdaterPlugin/updates/{$update_dir}/update_meta.php");
		$rel_updates_dir = "app/Plugins/UpdaterPlugin/updates/{$update_dir}/update_files";

		foreach ($update_meta["update_files_meta"] as $file => $command_desc) {

			switch ($command_desc["file_type"]) {

				case 'file':
					
					if ($command_desc["action"] == "copy") {
						$this->copyFile("{$rel_updates_dir}/{$command_desc['file']}", $file);
					} else if ($command_desc["action"] == "delete") {
						$this->deleteFile($file);
					}

					break;
				
				case 'directory':

					if ($command_desc["action"] == "copy") {
						$this->copyDirectory("{$rel_updates_dir}/{$command_desc['file']}", $file);
					} else if ($command_desc["action"] == "delete") {
						$this->deleteDirectory($file);
					}

					break;

				case 'language':

					$overwrite_lang_files = $this->overwriteLanguageFiles('overwrite_lang_files');
					$this->mergeLanguageFiles($file, "{$rel_updates_dir}/{$command_desc['file']}", $overwrite_lang_files);

					break;
				
			}


		}

		return true;
	}


	public function mergeLanguageFiles($file1, $file2, $swap = false) {

		$file1_rel_path = $file1;

		$file1 = base_path($file1);
		$file2 = base_path($file2);

		if (!file_exists($file1)) {
			$array1 = [];
		} else {
			$array1 = include $file1;
			if(!is_array($array1)) {
				$array1 = [];
			}
		}

		$array2 = include $file2;

		if ($swap)
			$merged_array = array_merge($array1, $array2);
		else
			$merged_array = array_merge($array2, $array1);

		$this->saveArrayToFile($merged_array, $file1_rel_path);
	}


	public function saveArrayToFile($merged_array, $file) {

		$array_dump_string = var_export($merged_array, true);
		$data_string = "<?php \n return \n {$array_dump_string} ; \n";

		$this->deleteFile($file);
		Storage::put($file, $data_string);
	}




	public function deleteFile($file) {
		if (Storage::has($file)) Storage::delete($file);
	}

	public function deleteDirectory($dir) {
		Storage::deleteDirectory($dir);
	}


	public function copyDirectory ($source, $destination) {

		$source_temp      = base_path($source);
		$destination_temp = base_path($destination);

       	$directory = opendir($source_temp); 
       
		//Create the copy folder location
		if(!file_exists($destination_temp))
			mkdir($destination_temp, 0777);	

		//Scan through the folder one file at a time
		while(($file = readdir($directory)) != false) {
			
			if($file == '.' || $file == '..')
				continue;

			if(is_dir("{$source_temp}/{$file}")) {
				self::copyDirectory("{$source}/{$file}", "{$destination}/{$file}");
			} else {
				//Copy each individual file 
		    	if(!copy("{$source_temp}/{$file}", "{$destination_temp}/{$file}"));
			}
		} 

	}


	public function copyFile($src_file, $dest_file) {

		if (Storage::has($dest_file)) Storage::delete($dest_file);
        Storage::copy($src_file, $dest_file);
	}



	public function Update($fileName, $tempUpdatesArray) 
	{

		$updateDir = basename($fileName, ".zip");

		$this->callUpdateRun("App\\Plugins\\UpdaterPlugin\\updates\\{$updateDir}\\{$updateDir}");

		$permission_check = $this->checkPermissionsForUpdateLoop($updateDir);

		if ($permission_check !== "") {
			throw new \Exception($permission_check . " " . trans('admin.should_be_made_writable'));
		}

		$this->updateLoop($updateDir);
		return true;
	}


	protected function callUpdateRun($className)
	{
		$updateClassInstance = new $className;
		$updateClassInstance->run();
	}




	public function extractUpdateFile($fileName) 
	{
		
		$extractPath = $this->updateExtractPath();
		$zipPath = $this->updateExtractPath($fileName);

		$zipper = new \ZipArchive;
			
		if ( $zipper->open($zipPath) === true ) {

            $zipper->extractTo($extractPath);
            $zipper->close();

        	return true;
        } else {
        	return false;
        }

	}



	protected function updateExtractPath($fileName = '')
	{
		return $fileName ? app_path("/Plugins/UpdaterPlugin/updates/{$fileName}") : app_path("/Plugins/UpdaterPlugin/updates/{$fileName}");
	}




	protected function downloadUpdate($fileName, $tempUpdatesArray) 
	{

		if(isset($tempUpdatesArray['updates'])) {


			foreach($tempUpdatesArray['updates'] as $product) {


				if(isset($product['updates'])) {


					foreach($product['updates'] as $update) {


						if($update['filename'] === $fileName) {
							$this->downloadFile(
								$update['file_url'],
								$this->updateFileDestPath($fileName)
							);

							return true;
						}

					}


				}


			}


		}

		return false;
		

	}



	protected function updateFileDestPath($fileName)
	{
		return app_path("/Plugins/UpdaterPlugin/updates/{$fileName}");
	}




	protected function downloadFile($URL, $destPath)
	{
		file_put_contents($destPath, fopen($URL, 'r'));
	}




	public function doUpdate($fileName)
	{
		ini_set('max_execution_time', 0);

		$tempUpdatesArray = $this->tempUpdatesArray();

		try {


			$success = $this->downloadUpdate($fileName, $tempUpdatesArray);

			if(!$success) {
				return ["status" => "error", "error_type" => "FILE_DOWNLOAD_ERROR", "error_text" => trans('admin.failed_to_download_file')];
			}



			$success = $this->extractUpdateFile($fileName);

			if(!$success) {
				return ["status" => "error", "error_type" => "FILE_EXTRACT_FAILED", "error_text" => trans('admin.file_extract_failed')];
			}



			$success = $this->Update($fileName, $tempUpdatesArray);

			if(!$success) {
				return ["status" => "error", "error_type" => "UPDATE_FAILED", "error_text" => trans('admin.update_failed')];
			}

			


			if($this->findProductIdByFilename($tempUpdatesArray, $fileName, $productID, $version)) {

				$this->UpdateLocalFile($productID, $version);

				return ["status" => "success"];

			}
			


		} catch(\Exception $e) {

			return ["status" => "error", "error_type" => "UNKNOWN_ERROR", "error_text" => $e->getMessage()];
		}
		
	}




	protected function findProductIdByFilename($array, $fileName, &$productID, &$version)
	{
		if(isset($array['updates'])) {


			foreach($array['updates'] as $product) {


				if(isset($product['updates'])) {


					foreach($product['updates'] as $update) {


						if($update['filename'] === $fileName) {
							$productID = $product['id'];
							$version = $update['version'];
							return true;
						}

					}


				}


			}


		}

		return false;
	}




	public function UpdateLocalFile($productID, $version)
	{
		$array = $this->localUpdateArray();
		$array[$productID] = $version;

		$json = json_encode($array);

		$localUpdateFilePath = $this->localUpdateFilePath(true);

		if(Storage::has($localUpdateFilePath)) {
			Storage::delete($localUpdateFilePath);
		}

		Storage::put($localUpdateFilePath, $json);
		return true;

	}







	protected $overwrite_lang_files = false;



	public function setOverwriteLanguageFiles($overwrite_lang_files = false)
	{
		$this->overwrite_lang_files = $overwrite_lang_files;
	}




	public function overwriteLanguageFiles()
	{
		return $this->overwrite_lang_files;
	}





	


	public function checkUpdates($url) 
	{

		$remoteUpdateArray = $this->remoteUpdatesArray($url);

		if(isset($remoteUpdateArray['status']) && $remoteUpdateArray['status'] == 'success') {

			//update success return response 
			$productList = [];
			$fileList = [];
			foreach($remoteUpdateArray['updates'] as $product) {
				array_push(
					$productList, 
					[
						$product['product'],
						$product['latest_version'],
						$product['updates'],
						isset($product['is_new']) ? $product['is_new'] : false,
					]
				);


				foreach($product['updates'] as $eachUpdate) {
					array_push($fileList, $eachUpdate['filename']);
				} 

			}


			if(empty($fileList)) {
				return ["status" => "ERROR", "error_type" => "NO_UPDATE", "message" => trans('admin.no_updates')];
			}

			return ["status" => "SUCCESS", "product_lists" => $productList, "file_lists" => $fileList];


		} else if(isset($remoteUpdateArray['status']) && $remoteUpdateArray['status'] == "error") {

			return ["status" => "ERROR", "error_type" => $remoteUpdateArray['error_code'], "message" => isset($remoteUpdateArray['error_text']) ? $remoteUpdateArray['error_text'] : $remoteUpdateArray['error_code']];

		}
		
		return ["status" => "ERROR", "error_type" => "NO_UPDATE", "message" => trans('admin.no_updates')];
	}





	public function remoteUpdatesArray($URL) 
	{

		$licenseKey = config('settings.app_license_key');
		$previousUpdates = $this->localUpdateArray();

		$previousUpdates = json_encode($previousUpdates);

		$fields = [
			"license_key" => $licenseKey,
			"products" => $previousUpdates ? $previousUpdates : "{}"
		];

		$error = false;
		$serverResponse = $this->postURL($URL, [], $fields, $error);
		


		

		$this->saveToTempUpdateFile($serverResponse);

		return !$error ? json_decode($serverResponse, true) : [];
	}


	protected function saveToTempUpdateFile($contents)
	{

		$tempUpdateFilePath = $this->tempUpdateFilePath(true);

		if(Storage::has($tempUpdateFilePath)) {
			Storage::delete($tempUpdateFilePath);
		}

		Storage::put($tempUpdateFilePath, $contents);
		return true;
	}


	protected function postURL($url, $headers, $fields, &$error = false)
	{
		try {

			$ch = curl_init ();
		    curl_setopt ( $ch, CURLOPT_URL, $url );
		    curl_setopt ( $ch, CURLOPT_POST, true );
		    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
		    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
		    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

		    $result = curl_exec ( $ch );
		    curl_close ($ch);
		    return $result;


		} catch(\Exception $e) {
			$error = true;
			return $e->getMessage();
		}
	}


	protected function buildQuery($fields)
	{
		$query = "";
		foreach($fields as $key => $value) {
			$query .= $key . '='.$value . '&';
		}
		return $query;
	}



	protected function tempUpdateFilePath($relativePath = false)
	{
		return $relativePath ? "app/Plugins/UpdaterPlugin/updates/updates_temp.json" : app_path("/Plugins/UpdaterPlugin/updates/updates_temp.json");
	}




	public function tempUpdatesArray() {
		try {
			$tempUpdateContent = file_get_contents($this->tempUpdateFilePath());
        	$json = json_decode($tempUpdateContent, true);
        	return is_null($json) ? [] : $json;
		} catch (\Exception $e) {
			return [];
		}
	}




	protected function localUpdateFilePath($relativePath = false) 
	{
		return $relativePath ? "app/Plugins/UpdaterPlugin/updates/updates.json" : app_path("/Plugins/UpdaterPlugin/updates/updates.json");
	}





	protected function localUpdateArray() 
	{
		try {
			$localUpdateContent = file_get_contents($this->localUpdateFilePath());
        	$json = json_decode($localUpdateContent, true);

        	return is_null($json) ? [] : $json;
		} catch (\Exception $e) {
			return [];
		}
	}


	/*
	public function doBackup() {
		ini_set('max_execution_time', 0);

		Artisan::call('backup:run');
		return Artisan::output();
	}
	*/


    //This method is checking read and write permissins
	public function checkReadWritePermissions(&$errors)
	{
		
		if(!$this->checkPermissionStoragePath()) {
			array_push($errors, storage_path(). ' '. trans('admin.should_be_made_writable'));
		}

		if(!$this->checkPermissionAppPath()) {
			array_push($errors, app_path(). ' '. trans('admin.should_be_made_writable'));
		}


		if(!$this->checkPermissionConfigPath()) {
			array_push($errors, base_path(). ' '. trans('admin.should_be_made_writable'));
		}

		if(!$this->checkPermissionPublicPath()) {
			array_push($errors, public_path(). ' '. trans('admin.should_be_made_writable'));
		}

		if(!$this->checkPermissionResourcesPath()) {
			array_push($errors, base_path("/resources"). ' '. trans('admin.should_be_made_writable'));
		}

		return count($errors) ? true : false;
	}


	protected function checkPermissionResourcesPath()
	{
		return is_writable(base_path("/resources"))? true : false;
	}

	public function checkPermissionStoragePath()
	{
		$storage_path = storage_path();

		if(is_writable($storage_path))
			return true;
		else
			return false;
	}

	public function checkPermissionAppPath()
	{
		$app_path = app_path();

		if(is_writable($app_path))
			return true;
		else
			return false;
	}


	public function checkPermissionConfigPath()
	{
		$config_path = base_path() . '/config';

		if(is_writable($config_path))
			return true;
		else
			return false;
	}

	public function checkPermissionPublicPath()
	{
		$public_path = public_path();

		if(is_writable($public_path))
			return true;
		else
			return false;
	}



	public function currentVersion()
	{
		$localUpdateArray = $this->localUpdateArray();

		try {

			reset($localUpdateArray);
			$first_key = key($localUpdateArray);

			return $localUpdateArray[$first_key];

		} catch(\Exception $e) {
			return '0.0.0';
		}

			
	}


}

    