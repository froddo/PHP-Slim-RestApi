$(document).ready(function () {
    // Update Submit hide
    $('#update').hide();

    // Get All Posts
    $.get('/api/posts', function (data) {

        let output = '<ul class="list-group">';
            $.each(JSON.parse(data), function (intex, post) {
                output += '<li class="list-group-item"> <strong class="text-danger">title: </strong> '+post.title+' | <strong class="text-success">body: </strong> '+post.body+' | <strong class="text-warning">category: </strong> '+post.name+' | <button class="edit btn btn-success btn-sm" id="'+post.id+'">Edit</button> <button id="'+post.id+'" class="delete btn btn-primary btn-sm">Delete</button></li>';
            });
        output+= '</ul>';
        $('#posts').html(output);
    });

    // Get All Categories
    $.get('/api/categories', function (data) {
        let output = '<ul class="list-group">';
        $.each(JSON.parse(data), function (index, category) {
            output += '<li class="list-group-item"> <strong class="text-primary">name:</strong>'+category.name+'</li>';
        });
        output+= '</ul>';
        $('#categories').html(output);
    });

    // Select Option Categories
    $.get('/api/categories', function (data) {
        let output = '<select class="form-control" id="category_id">';
        $.each(JSON.parse(data), function (index, category) {
            output += '<option value="'+category.id+'">'+category.name+'</option>';
        });
        output+= '</select>';
        $('#category_list').html(output);
    });

    // Create Post
    $('#postForm').submit(function (e) {
        e.preventDefault();

        let title = $('#title').val();
        let category_id = $('#category_id').val();
        let body = $('#body').val();

        if (title == '' || category_id == '' || body == ''){
            alert('Please Fill In all Forms');
        } else{

            $.post('/api/post/add',{
                title: title,
                category_id: category_id,
                body: body
            }).done(function (data) {
                alert(data);
                location.reload();
            });
        }

    });

    // Delete Post

    $('body').on('click','.delete',function (e) {
        e.preventDefault();
        let id = $(this).attr('id');

        deletePost(id);
    });
    function deletePost(id) {
        $.ajax({
            method:'DELETE',
            url: '/api/post/delete/'+id,
            contentType:"application/x-www-form-urlencoded",
        }).done(function(data) {
            alert(data);
            location.reload();
        });

    }

    // Edit Post

    $('body').on('click', '.edit', function (e) {
        e.preventDefault();
        $('#update').show();
        $('#submit').hide();
        let id = $(this).attr('id');
        let hidden = $('#hidden').val(id);
        $.ajax({
            url:'/api/post/'+id,
        }).done(function (data) {
            console.log("ok");
            $.each(JSON.parse(data), function (index, post) {
                let title = $('#title').val(post.title);
                let body = $('#body').val(post.body);
                let category_id = $('#category_id').val(post.category_id);
            });
        });
    });

    // Update Post

    $('#update').click(function (e) {
        e.preventDefault();
        let title = $('#title').val();
        let body = $('#body').val();
        let id = $('#hidden').val();
        let category_id = $('#category_id').val();
            console.log(category_id);
        if (title == '' || body == ''){
             alert('Please Fill In all Forms');
        } else {
              $.ajax({
                method:'PUT',
                  url: '/api/post/update/'+id,
                  data:{id:id, title:title, body:body, category_id:category_id}
              }).done(function (data) {
                alert(data);
                location.reload();
              });
        }
    })
});