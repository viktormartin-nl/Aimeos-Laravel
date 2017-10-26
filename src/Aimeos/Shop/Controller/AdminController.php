<?php

/**
 * @license MIT, http://opensource.org/licenses/MIT
 * @copyright Aimeos (aimeos.org), 2014-2016
 * @package laravel
 * @subpackage Controller
 */


namespace Aimeos\Shop\Controller;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


/**
 * Controller providing the ExtJS administration interface
 *
 * @package laravel
 * @subpackage Controller
 */
class AdminController extends Controller
{
	use AuthorizesRequests;


	/**
	 * Returns the initial HTML view for the admin interface.
	 *
	 * @param \Illuminate\Http\Request $request Laravel request object
	 * @return \Illuminate\Contracts\View\View View for rendering the output
	 */
	public function indexAction( Request $request )
	{
		if( Auth::check() === false
			|| $request->user()->can( 'admin', [AdminController::class, ['admin', 'editor', 'super']] ) === false
		) {
			return redirect()->guest( 'login' );
		}

		$siteId = $request->user()->siteid;
		$context = app( '\Aimeos\Shop\Base\Context' )->get( false );
		$siteManager = \Aimeos\MShop\Factory::createManager( $context, 'locale/site' );
		$siteCode = ( $siteId ? $siteManager->getItem( $siteId )->getCode() : 'default' );

		$param = array(
			'resource' => 'dashboard',
			'site' => Route::input( 'site', Input::get( 'site', $siteCode ) ),
			'lang' => Route::input( 'lang', Input::get( 'lang', $request->user()->langid ?: config( 'app.locale', 'en' ) ) )
		);

		return redirect()->route( 'aimeos_shop_jqadm_search', $param );
	}
}
