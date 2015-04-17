Instagram Hashtag Explorer
==========================

A small script that connects to the Instagram API with a user's credentials, retrieves the latest media tagged with a specified term (via this API call: https://instagram.com/developer/endpoints/tags/#get_tags_media_recent), and creates:
- a co-tag network (GDF format) to analyze in gephi (if two tags co-appear on an image, a link is set)
- a tabular file with data on the retrieved media
- a tabular file with user information (additional user info is retrieved via this API call: https://instagram.com/developer/endpoints/users/#get_users)

This uses the PHP Instagram API wrapper by Christian Metz (https://github.com/cosenary/Instagram-PHP-API).

For more information, check the wiki page: https://github.com/bernorieder/instagram-hashtag-explorer/wiki/Instagram-Hashtag-Explorer.
