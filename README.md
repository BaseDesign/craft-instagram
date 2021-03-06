# Instagram plugin for Craft CMS 3.x

Instagram integration for Craft CMS

## Requirements

This plugin requires Craft CMS 3.0.0 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require basedesign/craft-instagram

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for Instagram.

## Instagram Overview

This plugin uses two different methods to fetch Instagram media information:

1. Use the Instagram Basic Display API to fetch the 25 most recent media from an authenticated user

2. Append /media/?size=l to a given Instagram URL to fetch the media without authentication

## Configuring Instagram

To use the Instagram Basic Display API you will first need to create a [Facebook App](https://developers.facebook.com/apps), add the Instagram "Product". Then go to the Instagram -> Basic Display and create a new app. Finally add an Instagram tester account with the account you will be fetching media from. Facebook provides instructions in [its API documentation](https://developers.facebook.com/docs/instagram-basic-display-api/getting-started). Follow the instructions up until Step 3 included.

After installing the plugin you will need to access the plugin's Settings page and fill in your Instagram username together with a first long-lived access token for the API. Generate this long-lived access token in your Facebook app. It will renew automatically as soon as it expires.

## Using Instagram

This plugin includes 5 template variables:

* `craft.instagram.getUsername` in order to get the username of the authenticated Instagram user

* `craft.instagram.getUserUrl` in order to get the URL of the authenticated Instagram user's profile

* `craft.instagram.getAccessToken` in order to get the access token used to fetch data with the API

* `craft.instagram.getMediaFromUser` in order to get the 25 latest media from the authenticated Instagram user

* `craft.instagram.getMediaFromUrls(instagramUrlsArray)` in order to get the media information of specific Instagram media

## Instagram Roadmap

Some things to do, and ideas for potential features:

* Generate the first long-lived access token from the App ID and App Secret.

Brought to you by [Base Design](https://www.basedesign.com)
