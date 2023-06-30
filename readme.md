Commerce_Referrals
===

This Extra requires Commerce by Modmore.


[![commerce_referrals Referrals Grid](https://raw.githubusercontent.com/digitalpenguin/commerce_referrals/master/core/components/commerce_referrals/docs/img/commerce_referrals.png "Click to zoom in!")](https://raw.githubusercontent.com/digitalpenguin/commerce_referrals/master/core/components/commerce_referrals/docs/img/commerce_referrals.png)


This small module adds an extra page to the Commerce dashboard with a grid to keep partner company information.
Each partner is assigned a referral token which they can then use on the end of a product URL to send customers to your shop.

For example if a customer is assigned a token called `partnertoken`, they would add it on the end of your product URL with `?ref=partnertoken`.

As you can see here:
`https://example.com/shop/product/product.html?ref=partnertoken`

When a customer is referred to a product in your shop and adds that product to the shopping cart, the module will check if a partner exists for 
that token. If so, the token is added to the order.

The manager user can then see if this is a referral order. A referral section is added to the order detail page in Commerce.
The partner company information is displayed so they can take whatever action they've agreed to.

*Install*

- Install the package and then activate the module in the configuration tab of the Commerce dashboard.

*Usage*

- In the Commerce dashboard, click on the Referrals tab and then select **Referrers** in the subnav.
- Add details of a partner company that will refer customers to your products.
- One of the details will be a 'token'. The referrer then adds this token on the end of the URL and their referral will then be recorded. 

*System Setting*

- *commerce_referrals.tab_position* - allows you to set the position in the Commerce nav menu that the referrals tab is added. Value should be an integer. 

[![commerce_referrals Referrers Grid](https://raw.githubusercontent.com/digitalpenguin/commerce_referrals/master/core/components/commerce_referrals/docs/img/commerce_referrers.png "Click to zoom in!")](https://raw.githubusercontent.com/digitalpenguin/commerce_referrals/master/core/components/commerce_referrals/docs/img/commerce_referrers.png)

![Screenshot 2023-06-30 at 19-30-15 Commerce  raquo Order 00103 MODX 3](https://github.com/digitalpenguin/commerce_referrals/assets/5160368/068cbeb6-1ef5-426f-a996-4b18986534ba)