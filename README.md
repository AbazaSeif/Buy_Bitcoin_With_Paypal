This is a framework in PHP and MySQL to sell Bitcoin using Paypal.

It generates a Paypal button on the spot with the current MtGox price. In order to minimize chargebacks, it asks for a mobile phone number and sends an authentication PIN.

In the Paypal receipt the total BTC and the phone number are written, in order to avoid using stolen Paypal accounts.

The phone numbers are hashed in the database.

There is a limit for new clients. Recurring customers have this limit raised.

All purchases are written in a public ledger (Hall of Fame), and the user is told that they are paying to *belong* to this ledger. In this way, the user (and yourself) can easily explain to PayPal what the user is paying for (belonging to this _Hall of Fame_).


This setup aims to minimize the three most common forms of scam:

a) The customer pays with stolen credit card (discouraged by writing his mobile phone number).

b) The customer "regrets" having done this payment (discouraged by setting limits to new clients).

c) The customer is using a stolen Paypal account (discouraged by asking for his mobile phone number).


APIs used:

	*MtGox*, to fetch the current exchange rate.

	*Paypal*, to automate payments and generate the Buy Now button with the correct price.

	*TextMagic*, in order to send the confirmation PIN to the mobile phone.

