Commerce_Referrals
===

This small module adds an extra page to the Commerce dashboard with a grid to keep partner company information.
Each partner is assigned a referral token which they can then use on the end of a product URL to send customers to your shop.

For example if a customer is assigned a token called `partnertoken`, they would add it on the end of your product URL with `?ref=partnertoken`.

As you can see here:
`https://example.com/shop/product/product.html?ref=partnertoken`

When a customer is referred to a product in your shop and adds that product to the shopping cart, the module will check if a partner exists for 
that token. If so, the token is added to the order.

The manager user can then see if this is a referral order. A referral section is added to the order detail page in Commerce.
The partner company information is displayed so they can take whatever action they've agreed to.

 
[![commerce_referrals Order](https://github.com/digitalpenguin/commerce_referrals/blob/master/core/components/commerce_referrals/docs/img/referrer-in-order.png "Click to zoom in!")](https://raw.githubusercontent.com/digitalpenguin/commerce_referrals/master/core/components/commerce_referrals/docs/img/referrer-in-order.png)
