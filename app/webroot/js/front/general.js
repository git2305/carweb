$(document).ready(function () {

    var url = window.location.pathname;
    var lastSegment = url.split('/').pop();


    if (lastSegment == '' || lastSegment == 'sellVehicles') {
        $.ajax({
            url: siteUrl + '/Users/getAuctionData',
            success: function (data)
            {
                $('#auctionData').html(data);
                $('[data-countdown]').each(function () {
                    var $this = $(this);
                    $this.countdown(Date.parse($this.data('countdown')), function (event) {
                        
                        if (event.type == 'finish') {
                            $.ajax({
                                url: siteUrl + '/Users/sellCarToUser',
                                data: {vehicle_id: $(this).attr('data-vehicleid')},
                                type: 'POST',
                                success: function (res) {
                                    if (res == 'emailsuccess') {
                                        $this.parent().parent().parent().remove();
                                        alertify.notify('Request has been send successfully.', 'success');
                                        return false;
                                    } else if (res == 'success') {
                                        alertify.notify('This Vehicle have been removed from list.', 'success');
                                        return false;
                                    } else if (res == 'fail') {
                                        $this.parent().parent().parent().remove();
                                        alertify.notify('We are unable to send your request.', 'success');
                                        return false;
                                    } else {
                                        return false;
                                    }
                                },
                                error: function (errormessage) {
                                    console.log(errormessage);
                                },
                            });
                        } else {
                            $this.html(event.strftime('<span>%I:%M:%S</span>'));
                        }
                    });
                });
            },
            error: function (errormessage) {
                console.log(errormessage);
            },
        });
    }

    setInterval(function () {
        $('[data-countdown]').each(function () {
            if ($('#expireTime_' + $(this).attr('data-vehicleid')).text() <= '04:21:00') {
                $('.vehicleSell_' + $(this).attr('data-vehicleid')).css('border', '1px solid red');
                $('.vehicleSell_' + $(this).attr('data-vehicleid')).find('.btn-Preis').attr('disabled', 'disabled');
                $('.vehicleSell_' + $(this).attr('data-vehicleid')).find('.btn-Preis').addClass('vehicle-sold-button');
                $('.vehicleSell_' + $(this).attr('data-vehicleid')).find('.vehicle-sold').removeClass('hidden');
                $('.vehicleSell_' + $(this).attr('data-vehicleid')).find('.vehicle-in-auction').addClass('hidden');
            }
        });
    }, 1000);

});
