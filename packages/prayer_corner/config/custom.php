<?php

return array(
    'Status' => [
        0 => 'Pending',
        1 => 'Approved',
        2 => 'Rejected',
        3 => 'Not authorized' 
    ],
    'AdminEmail' => [
            'FromEmail' => 'DivineWordGifts@uscsvd.org',
            'Subject' =>   [
	         'en' => 'English Prayer Corner Request Has Been Added',
		'pl' => 'Polish Prayer Corner Request Has Been Added',
		'sp' => 'Spanish Prayer Corner Request Has Been Added',
		'po' => 'Portuguese Prayer Corner Request Has Been Added',
		'vi' => 'Vietnamese Prayer Corner Request Has Been Added'
	    ],
    	    'Template' =>  '<p>You have recieved a submission for prayer corner request with the following information:</p>
                            <p>-------------------------</p>
                            <p><strong>Name: </strong>{first_name} {last_name}</p>
                            <p><strong>Email: </strong>{email}</p>
                            <p><strong>City: </strong>{city}</p>
                            <p><strong>Country: </strong>{country}&nbsp;</p>
                            <p><strong>Prayer request: </strong>{prayer_request}</p>
                            <p><strong>Post to the public Prayer Wall: </strong>{post_public}</p>
                            <p><strong>Consent for Contact Via Email:&nbsp;</strong>{email_consent}</p>
                            <p>-------------------------</p>
                            <p>Regard,</p>
                            <p>Society of Divine Word</p>'
    ],
    'AdminEmailLanguage' => [
        'en' => 'jhorowski@uscsvd.org,Development@uscsvd.org',
        'pl' => 'jhorowski@uscsvd.org,MisjonarzeWerbisci@uscsvd.org',
        'sp' => 'jhorowski@uscsvd.org,Development@uscsvd.org',
        'po' => 'jhorowski@uscsvd.org,Development@uscsvd.org',
        'vi' => 'Unghotruyengiao@dwci.edu'
    ]
);