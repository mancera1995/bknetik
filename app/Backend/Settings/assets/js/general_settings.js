(function ($)
{
	"use strict";

	$(document).ready(function ()
	{

		$('#booknetic_settings_area').on('click', '.settings-save-btn', function()
		{
			var timeslot_length										= $("#input_timeslot_length").val(),
				default_appointment_status							= $("#input_default_appointment_status").val(),
				min_time_req_prior_booking							= $("#input_min_time_req_prior_booking").val(),
				available_days_for_booking							= $("#input_available_days_for_booking").val(),
				slot_length_as_service_duration						= $("#input_slot_length_as_service_duration").val(),
				week_starts_on										= $("#input_week_starts_on").val(),
				date_format											= $("#input_date_format").val(),
				time_format											= $("#input_time_format").val(),
				google_maps_api_key									= $("#input_google_maps_api_key").val(),
				flexible_timeslot									= $('#input_flexible_timeslot').val(),
				client_timezone_enable								= $("#input_client_timezone_enable").is(':checked')?'on':'off',
				change_status_page_id								= $("#input_change_status_page_id").select2('val'),
				google_recaptcha				    				= $("#input_google_recaptcha").is(':checked')?'on':'off',
				google_recaptcha_site_key							= $("#input_google_recaptcha_site_key").val(),
				google_recaptcha_secret_key							= $("#input_google_recaptcha_secret_key").val(),
				remove_branding				        				= $("#input_remove_branding").is(':checked')?'on':'off',
				timezone			                				= $("#input_timezone").val(),
				allow_admins_to_book_outside_working_hours			= $("#input_allow_admins_to_book_outside_working_hours").is(':checked')?'on':'off',
				only_registered_users_can_book			    		= $("#input_only_registered_users_can_book").is(':checked')?'on':'off',
				new_wp_user_on_new_booking			    		    = $("#input_new_wp_user_on_new_booking").is(':checked')?'on':'off';


			booknetic.ajax('save_general_settings', {
				timeslot_length: timeslot_length,
				default_appointment_status: default_appointment_status,
				min_time_req_prior_booking: min_time_req_prior_booking,
				available_days_for_booking: available_days_for_booking,
				slot_length_as_service_duration: slot_length_as_service_duration,
				week_starts_on: week_starts_on,
				date_format: date_format,
				time_format: time_format,
				google_maps_api_key: google_maps_api_key,
				flexible_timeslot: flexible_timeslot,
				client_timezone_enable: client_timezone_enable,
				change_status_page_id: change_status_page_id,
				google_recaptcha: google_recaptcha,
				google_recaptcha_site_key: google_recaptcha_site_key,
				allow_admins_to_book_outside_working_hours: allow_admins_to_book_outside_working_hours,
				only_registered_users_can_book: only_registered_users_can_book,
				new_wp_user_on_new_booking: new_wp_user_on_new_booking,
				google_recaptcha_secret_key: google_recaptcha_secret_key,
				remove_branding: remove_branding,
				timezone: timezone
			}, function ()
			{
				booknetic.toast(booknetic.__('saved_successfully'), 'success');
			});

		});

		$("#input_timeslot_length, #input_min_time_req_prior_booking, #input_max_time_req_prior_booking").select2({
			theme: 'bootstrap',
			placeholder: booknetic.__('select'),
			allowClear: true
		});

		$("#input_date_format, #input_time_format").select2({
			theme: 'bootstrap',
			placeholder: booknetic.__('select')
		});

		var fadeSpeed = 0;
		$('#input_google_recaptcha').on('change', function ()
		{
			if( $(this).is(':checked') )
			{
				$('div[data-hide-key="recaptcha"]').fadeIn(fadeSpeed);
			}
			else
			{
				$('div[data-hide-key="recaptcha"]').fadeOut(fadeSpeed);
			}
		}).trigger('change');
		fadeSpeed = 200;

	});

})(jQuery);