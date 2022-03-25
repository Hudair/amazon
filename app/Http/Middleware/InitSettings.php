<?php
namespace App\Http\Middleware;
use App\Models\Customer;
use App\Helpers\ListHelper;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
class InitSettings
{
	public function handle($request, Closure $next)
	{
		if (!$request->is("install*")) {
			goto WvKAO;
		}
		return $next($request);
		WvKAO:setSystemConfig();
		View::addNamespace("theme", theme_views_path());
		if (!Auth::guard("web")->check()) {
			goto LCtG1;
		}
		if ($request->is("admin/*") || $request->is("account/*")) {
			goto rSFuH;
		}
		return $next($request);
		goto cqZLV;
		rSFuH:$this->can_load();
		cqZLV:
		if (!$request->session()->has("impersonated")) {
			goto egl5j;
		}
		Auth::onceUsingId($request->session()->get("impersonated"));
		egl5j:$user = Auth::guard("web")->user();
		if (!(!$user->isFromPlatform() && $user->merchantId())) {
			goto MuLf1;
		}
		setShopConfig($user->merchantId());
		MuLf1:$permissions = Cache::remember(
			"permissions_" . $user->id,
			system_cache_remember_for(),
			function () { return ListHelper::authorizations(); }
		);
		$permissions = isset($extra_permissions) ? array_merge($extra_permissions, $permissions) : $permissions;
		config()->set("permissions", $permissions);
		if (!$user->isSuperAdmin()) {
			goto u7FiC;
		}
		$slugs = Cache::remember("slugs", system_cache_remember_for(), function () { return ListHelper::slugsWithModulAccess(); });
		config()->set("authSlugs", $slugs);
		u7FiC:LCtG1:
		if (!is_incevio_package_loaded("zipcode")) {
			goto tTWw7;
		}
		$user = Auth::guard("customer")->user();
		if ($user instanceof Customer && $user->address) {
			goto R2ixJ;
		}
		$zipCode = session("zipcode_default") ?? get_from_option_table("zipcode_default");
		goto dKu_4;
		R2ixJ:$zipCode = $user->address->zip_code;
		dKu_4:Session::put("zipcode_default", $zipCode);
		tTWw7:return $next($request);
	}
	private function can_load()
	{
		v_CVc:incevioAutoloadHelpers(getMysqliConnection());
	}
}