First download the Magento with Sample Data from here:
https://magento.com/tech-resources/download

For infinite Scrolling of Products, I would suggest to use the extensions available on marketplace. One extension which I found free is:
https://marketplace.magento.com/sparsh-magento-2-ajax-infinite-scroll-extension.html

For the Assignment of Price to be fetched from API, I have followed the below approach:
=> I have created an Magento Extension in which Admin can set the configueration to Enable the module for specific store.
=> Admin can choose the Price should be updated by Cron or at Run time. I would suggest to use the Cron Option as Run Time price calling feature would be impacting on Performance of page loading.
=> If Run time option is selected, a Plugin will be called which will invoke the API set in Admin and will fetch the Price for specific sku and price will be shown in front-end.
=> If Cron option would be selected, then a Cron will be executed at scheduled time and cron will update the Price in Product table.

Note*- As I did not had the API format and response format, so I have just mock the code of API and response but it might require few changes as per API calling and response parsing.
