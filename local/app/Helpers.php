<?php
function dispayTimeStamp($timeStamp = null)
{
	$date = $timeStamp;
	$date->setTimezone(new DateTimeZone(Config::get('app.timezone_display')));
	return $date;
}

function profilePicture()
{
	if(!Auth::guest())
	{
		$imagenm = Auth::user()->profile_picture;
		$image = URL::asset('local/assets/images/admin/avatar.jpg');
		if($imagenm != "" || $imagenm != null)
		{
			$path = URL::asset('local/storage/upload/profile-pictures/');
			if(File::exists(base_path('storage/upload/profile-pictures/'.$imagenm)))
			{
			    $image = $path.'/'.$imagenm;
			}
		}
		return $image;
	}
}

