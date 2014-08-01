StarRatingBundle
================

**StarRatingBundle** is a Symfony Bundle for easy star rating system. **StarRatingBundle** can be used with your own Javascript library but it also includes [jQuery Raty](https://github.com/wbotelhos/raty) plugin if you'd like to install and use the bundle as-it-is. 

## Installation

* Install the bundle via Composer:
	
		composer require ideato/star-rating-bundle "dev-master"

* Add the IdeatoStarRatingBundle to your application kernel:

		// app/AppKernel.php
		public function registerBundles()
		{
    			return array(
        			// ...
        			new Ideato\StarRatingBundle\IdeatoStarRatingBundle(),
        			// ...
    			);
		}

* Update the database schema with Doctrine:

		app/console doctrine:schema:update --force

* Include the routing configuration:

		#app/config/routing.yml
		_ideato_starrating:
		    resource: "@IdeatoStarRatingBundle/Resources/config/routing.yml"
		    
## Configuration

In order to include static files in case of you want to use [jQuery Raty](https://github.com/wbotelhos/raty) plugin included, you need to:

* Add the bundle within the allowed Assetic bundles:

		#app/config.yml
		assetic:
			#...
			bundles:        [ IdeatoStarRatingBundle ]

* Load static files:

        {% javascripts
        	'//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js'
        	'@IdeatoStarRatingBundle/Resources/public/jquery.raty.js'
        	'@IdeatoStarRatingBundle/Resources/public/jquery.starrating.js'
        %}
        <script type="text/javascript" src="{{ asset_url }}"></script>
        {% endjavascripts %}

        {% stylesheets
        	"@IdeatoStarRatingBundle/Resources/public/jquery.raty.css" filter="cssrewrite" %}
        <link rel="stylesheet" href="{{ asset_url }}" />
        {% endstylesheets %}
		

* Install the assets and regenerate static files:

		app/console assets:install web
		app/console assetic:dump
		

## Usage

If you chose to use the jQuery plugin included, you simply need to render the star rating controller in your pages:

		{{ render(
			controller( "IdeatoStarRatingBundle:StarRating:displayRate", {
				contentId: post.id
			})
		) }}

	
You just need to customize the `contentId` param according to the id of your element. The controller automatically will render the rating stars with the current score enabled. 
	Something like the following screenshot:
	
![](Resources/doc/starrating.png)

In this way everything is already enabled and users can start to rate your resources.


### Bonus

You can completely rewrite the rating logic within the [jquery.starrating.js](Resources/public/jquery.starrating.js). The easiest way is to copy and paste the file within your bundle and use your own Javascript file.

## TODO

* Add new table for registering single rate
* Prevent multiple vote


