<?php
// index.php
define('ONLINE_BOOKING_TEXT', 'Online Booking');
define('CHECK_IN_DATE_TEXT','Check-in Date');
define('CHECK_OUT_DATE_TEXT','Check-out Date');
define('ADULT_OR_ROOM_TEXT','Adult/Room');
define('SEARCH_TEXT','Search');
define('ENTER_CHECK_IN_DATE_ALERT','Please Enter Check-In Date');
define('ENTER_CHECK_OUT_DATE_ALERT','Please Enter Check-Out Date');
define('CURRENCY_TEXT','Currency');

//booking-search.php
define('SEARCH_INPUT_TEXT','Search Input');
define('CHECK_IN_D_TEXT','Check-in Date');
define('CHECK_OUT_D_TEXT','Check-out Date');
define('TOTAL_NIGHTS_TEXT','Total Nights');
define('ADULT_ROOM_TEXT','Adult/Room');
define('SEARCH_RESULT_TEXT','Search Result');
define('SELECT_ONE_ROOM_ALERT','Please Select atleast one room');
define('MAX_OCCUPENCY_TEXT','Max Occupancy');
define('SELECT_NUMBER_OF_ROOM_TEXT','Select Number of Room');
define('ADULT_TEXT','Adult');
define('TOTAL_PRICE_OR_ROOM_TEXT','Total Price / Room');
define('VIEW_PRICE_DETAILS_TEXT','View Price Details');
define('HIDE_PRICE_DETAILS_TEXT','Hide Price Details');
define('CONTINUE_TEXT','Continue');
define('MON','Mon');
define('TUE','Tue');
define('WED','Wed');
define('THU','Thu');
define('FRI','Fri');
define('SAT','Sat');
define('SUN','Sun');
define('MONTH', 'Month');
define('CHILD_PER_ROOM_TEXT','Child / Room');
define('WITH_CHILD','with child'); 
define('CHILD_TEXT','Child');
define('NONE_TEXT','None');
define('MODIFY_SEARCH_TEXT','Modify Search');
define('VIEW_ROOM_FACILITIES_TEXT','View Room Facilities');
define('VIEW_NIGHTLY_PRICE_TEXT','View Nightly Price');
define('CALENDAR_AVAILABILITY_TEXT','Calendar Availability');
define('CHECK_AVILABILITY','Check Availability');
define('BACK_TEXT','Back'); 
define('SORRY_ONLINE_BOOKING_CURRENTLY_NOT_AVAILABLE_TEXT','Sorry online booking currently not available. Please try later.');
define('SORRY_YOU_HAVE_ENTERED_A_INVALID_SEARCHING_CRITERIA_TEXT','Sorry you have entered a invalid searching criteria. Please try with invalid searching criteria.');
define('MINIMUM_NUMBER_OF_NIGHT_SHOULD_NOT_BE_LESS_THAN_TEXT','Minimum number of night should not be less than.');
define('PLEASE_MODIFY_YOUR_SEARCHING_CRITERIA_TEXT','Please modify your searching criteria.');
define('BOOKING_NOT_POSSIBLE_FOR_CHECK_IN_DATE_TEXT','Booking not possible for check in date:');
define('PLEASE_MODIFY_YOUR_SEARCHING_CRITERIA_TO_HOTELS_DATE_TIME_TEXT','Please modify your search  criteria according to hotels date time.');
define('HOTELS_CURRENT_DATE_TIME_TEXT','Hotels Current Date Time:');
define('SORRY_NO_ROOM_AVAILABLE_AS_YOUR_SEARCHING_CRITERIA_TRY_DIFFERENT_DATE_SLOT','Sorry no room available as your searching criteria. Please try with different date slot.');
define('PER_ROOM_TEXT','per Room');
define('AND_TEXT','and');
define('FOR_TEXT','for');
//booking_details.php
define('BOOKING_DETAILS_TEXT','Booking Details');
define('CHECKIN_DATE_TEXT','Check-In Date');
define('CHECKOUT_DATE_TEXT','Check-Out Date');
define('TOTAL_NIGHT_TEXT','Total Nights');
define('TOTAL_ROOMS_TEXT','Total Rooms');
define('NUMBER_OF_ROOM_TEXT','Number of Room');
define('ROOM_TYPE_TEXT','Room Type');
define('MAXI_OCCUPENCY_TEXT','Max Occupancy');
define('GROSS_TOTAL_TEXT','Gross Total');
define('SUB_TOTAL_TEXT','Sub Total');
define('TAX_TEXT','Tax');
define('GRAND_TOTAL_TEXT','Grand Total');
define('ADVANCE_PAYMENT_TEXT','Advance Payment');
define('OF_GRAND_TOTAL_TEXT','of Grand Total');
define('CUSTOMER_DETAILS_TEXT','Customer Details');
define('EXISTING_CUSTOMER_TEXT','Existing Customer');
define('EMAIL_ADDRESS_TEXT','Email Address');
define('FETCH_DETAILS_TEXT','Login');
define('OR_TEXT','OR');
define('NEW_CUSTOMER_TEXT','New Customer');
define('TITLE_TEXT','Title');
define('MR_TEXT','Mr');
define('MS_TEXT','Ms');
define('MRS_TEXT','Mrs');
define('MISS_TEXT','Miss');
define('DR_TEXT','Dr');
define('PROF_TEXT','Prof');
define('FIRST_NAME_TEXT','First Name');
define('LAST_NAME_TEXT','Last Name');
define('ADDRESS_TEXT','Address');
define('CITY_TEXT','City');
define('STATE_TEXT','State');
define('POSTAL_CODE_TEXT','Postal Code');
define('COUNTRY_TEXT','Country');
define('PHONE_TEXT','Phone');
define('FAX_TEXT','Fax');
define('EMAIL_TEXT','Email');
define('PAYMENT_BY_TEXT','Payment by');
define('FIELD_REQUIRED_ALERT','This field is required');
define('ADDITIONAL_REQUESTS_TEXT','Any additional requests');
define('I_AGREE_WITH_THE_TEXT',' I agree with the');
define('TERMS_AND_CONDITIONS_TEXT','Terms & Conditions');
define('CONFIRM_TEXT','Confirm');
define('CHECKOUT_TEXT','Checkout');
define('BD_INC_TAX','Including Tax');
define('HOME_TEXT','Home');
//process.class.php
define('INV_BOOKING_NUMBER','Booking Number');
define('INV_CUSTOMER_NAME','Customer Name');
define('INV_ADULT','Adult');
define('INV_PAY_DETAILS','Payment Details');
define('INV_PAY_OPTION','Payment Option');
define('INV_TXN_ID','Transaction ID');
define('PP_REGARDS','Regards');
define('PP_CARRY','You will need to carry a print out of this e-mail and present it to the hotel on arrival and check-in. This e-mail is the confirmation voucher for your booking.');
//offlinecc-payment.php
define('CC_DETAILS','Credit Card Details');
define('CC_HOLDER','Card Holder Name');
define('CC_TYPE','Credit Card Type');
define('CC_NUMBER','Credit Card Number');
define('CC_EXPIRY','Expiry Date');
define('CC_AMOUNT','Total Amount');
define('CC_TOS1','I agree to allow');
define('CC_TOS2','to deduct');
define('CC_TOS3','from my credit card');
define('CC_SUBMIT','Submit');
//booking-confirm.php
define('BOOKING_COMPLETED_TEXT','Booking Completed');
define('THANK_YOU_TEXT','Thank You');
define('YOUR_BOOKING_CONFIRMED_TEXT','Your Booking confirmed');
define('INVOICE_SENT_EMAIL_ADDRESS_TEXT','Invoice sent in your email address');
define('BACK_TO_HOME','Back to Home');
//booking-failure.php
define('BOOKING_FAILURE_TEXT','Booking Failure');
define('BOOKING_FAILURE_ERROR_9', 'Direct access to this page is restricted.');

define('BOOKING_FAILURE_ERROR_13', 'Somebody else already acquire  the reservation lock on rooms specified by you. Reservation lock will be automatically released after few minutes on booking completion or failure by the other person. Please modify your search criteria and try again.');

define('BOOKING_FAILURE_ERROR_22', 'Undefined payment method selected. Please contact administrator.');

define('BOOKING_FAILURE_ERROR_25', 'Failed to send email notification. Please contact technical support.');

define('BOOKING_FAILURE_ERROR_26', 'Booking process Cancelled! if any query please contact us!');

//wizard
define('SELECT_DATES_TEXT','Select Dates');
define('ROOMS_TEXT','Rooms');
define('RATES_TEXT','Rates');
define('YOUR_DETAILS_TEXT','Your Details');
define('PAYMENT_TEXT','Payment');
define('LOADING_TEXT','loading');
define('BTN_CANCEL','Cancel');

define('ID_TYPE',' Identity Type');
define('ID_NUMBER',' Identity Number');

?>