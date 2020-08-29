$(document).on("click",'.like',function() {
    var like = $(this);
    var postId = $(this).data('post_id');
    var likesCount = $(this).data('likes_count');
    var icon = $(this).find('.icon-heart')
    $.ajax({
        type: "POST",
        url: "/posts/like?id=" + postId,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            like.addClass('liked');
            like.removeClass('like');
            icon.removeClass('far');
            icon.addClass('fas');
            likesCount++;
            if (data.likesCount) {
                like.find('.likes-count').text(data.likesCount);
            }

        },
        error: function (errormessage) {

        }
    });

});

$(document).on("click",'.liked',function() {
    var like = $(this);
    var icon = $(this).find('.icon-heart')
    var postId = $(this).data('post_id');
    var likesCount = $(this).data('likes_count');

    $.ajax({
        type: "POST",
        url: "/posts/un-like?id=" + postId,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            icon.addClass('far');
            icon.removeClass('fas');
            likesCount - 1;
            like.addClass('like');
            like.removeClass('liked');

            if (data.likesCount) {
                like.find('.likes-count').text(data.likesCount);
            }
        },
        error: function (errormessage) {

        }
    });

});

$(document).on("click",'.star',function() {
    var star = $(this);
    var postId = $(this).data('post_id');
    var icon = $(this).find('.icon-star')
    $.ajax({
        type: "POST",
        url: "/posts/star?id=" + postId,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            star.addClass('starred');
            star.removeClass('star');
            icon.removeClass('far');
            icon.addClass('fas');
        },
        error: function (errormessage) {

        }
    });

});

$(document).on("click",'.starred',function() {
    var star = $(this);
    var icon = $(this).find('.icon-star')
    var postId = $(this).data('post_id');

    $.ajax({
        type: "POST",
        url: "/posts/un-star?id=" + postId,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            icon.addClass('far');
            icon.removeClass('fas');
            star.addClass('star');
            star.removeClass('starred');
        },
        error: function (errormessage) {

        }
    });

});

$(document).on("click",'.subscribe',function() {
    var subscribe = $(this);
    var userId = $(this).data('user_id');
    var subscribersCount = $(this).data('subscribers_count');
    $.ajax({
        type: "POST",
        url: "/profile/subscribe?id=" + userId,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            subscribe.removeClass('btn-outline-danger');
            subscribe.addClass('btn-secondary');
            subscribe.text('Отписаться');
            subscribe.removeClass('subscribe')
            subscribe.addClass('subscribed')
            subscribersCount++;
            if (data.subscribersCount) {
                subscribe.find('.subscribers-count').text(data.subscribersCount);
            }

        },
        error: function (errormessage) {

        }
    });

});

$(document).on("click",'.subscribed',function() {
    var subscribe = $(this);
    var userId = $(this).data('user_id');
    var subscribersCount = $(this).data('subscribers_count');


    $.ajax({
        type: "POST",
        url: "/profile/un-subscribe?id=" + userId,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            subscribersCount - 1;
            subscribe.removeClass('btn-secondary');
            subscribe.addClass('btn-outline-danger');
            subscribe.text('Подписаться');
            subscribe.removeClass('subscribed')
            subscribe.addClass('subscribe')
            if (data.subscruberdCount) {
                subscribe.find('.subscribers-count').text(data.subscruberCount);
            }
        },
        error: function (errormessage) {

        }
    });

});

$(document).on("click",'.like-com',function() {
    var like = $(this);
    var commentId = $(this).data('comment_id');
    var likesCount = $(this).data('likes_count');
    var icon = $(this).find('.icon-heart')
    $.ajax({
        type: "POST",
        url: "/comments/like?id=" + commentId,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            like.addClass('liked-com');
            like.removeClass('like-com');
            icon.removeClass('far');
            icon.addClass('fas');
            likesCount++;
            if (data.likesCount) {
                like.find('.likes-com-count').text(data.likesCount);
            }

        },
        error: function (errormessage) {

        }
    });

});

$(document).on("click",'.liked-com',function() {
    var like = $(this);
    var icon = $(this).find('.icon-heart')
    var commentId = $(this).data('comment_id');
    var likesCount = $(this).data('likes_count');

    $.ajax({
        type: "POST",
        url: "/comments/un-like?id=" + commentId,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            icon.addClass('far');
            icon.removeClass('fas');
            likesCount - 1;
            like.addClass('like-com');
            like.removeClass('liked-com');

            if (data.likesCount) {
                like.find('.likes-count').text(data.likesCount);
            }
        },
        error: function (errormessage) {

        }
    });

});

$(document).on('click', '.reply-btn', function() {
    var btn = $(this);
    window.btn = btn;
   /* console.log($(this).parent('.reply-js'));
    console.log($(this).parent('.reply-js').find('.js-reply-form'));*/
    var form = $(this).closest('.reply-js').find('.js-reply-form');
    form.toggleClass('reply-block-hidden');
});
