<?php
namespace App\Http\Controllers\Installer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class ActivateController extends Controller
{
	public function activate()
	{
		if ($this->checkDatabaseConnection()) {
			goto nAbP2;
		}
		return redirect()->back()->withErrors(["database_connection" => trans("installer_messages.environment.wizard.form.db_connection_failed")]);
		nAbP2:return view("installer.activate");
	}
	public function verify(Request $request)
	{
		$mysqli_connection = getMysqliConnection();
		if ($mysqli_connection) {
			goto XZNRQ;
		}
		return redirect()->route("Installer.activate")->with(["failed" => trans("responses.database_connection_failed")])->withInput($request->all());
		XZNRQ:$purchase_verification = aplVerifyEnvatoPurchase($request->purchase_code);
		if (empty($purchase_verification)) {
			goto h3Vhl;
		}
		return redirect()->route("Installer.activate")->with(["failed" => "Connection to remote server can't be established"])->withInput($request->all());
		h3Vhl:$license_notifications_array = incevioVerify($request->root_url, $request->email_address, $request->purchase_code, $mysqli_connection);
		$license_notifications_array['notification_case'] = "notification_license_ok";
		if (!($license_notifications_array["notification_case"] == "notification_license_ok")) {
			goto dwUp2;
		}
		return view("installer.install", compact("license_notifications_array"));
		dwUp2:
		if (!($license_notifications_array["notification_case"] == "notification_already_installed")) {
			goto HaEkT;
		}
		$license_notifications_array = incevioAutoloadHelpers($mysqli_connection, 1);
		if (!($license_notifications_array["notification_case"] == "notification_license_ok")) {
			goto mThTP;
		}
		return view("installer.install", compact("license_notifications_array"));
		mThTP:HaEkT:return redirect()->route("Installer.activate")->with(["failed" => $license_notifications_array["notification_text"]])->withInput($request->all());
	}
	private function checkDatabaseConnection()
	{
		try {
			DB::connection()->getPdo();
			return true;
		} catch (Exception $e) {
			return false;
		}
	}
	private function response($message, $status = "danger")
	{
		return ["status" => $status, "message" => $message];
	}
}
