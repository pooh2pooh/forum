function UpdateProfile(user_id) {

	// Загружаем новый аватар пользователя
	var formData = new FormData
	formData.append('user_avatar', $("#userAvatarInput").prop('files')[0])

	$.ajax({
		url: 'settings.php?user_id=' + user_id,
		type: 'POST',
		data: formData,
		processData: false,
    contentType: false,
		success: function(data) {
			console.log('SUCCESS update avatar user')
			location.href = '/'
		},
		error: function() { console.log('ERROR update avatar user') }
	})

	// Обновляем данные пользователя
	$.ajax({
		url: 'settings.php?user_id=' + user_id,
		type: 'POST',
		data: {
			username: $('#userNameInput').val(),
			lastfm: $('#lastfmInput').val()
		},
		success: function(data) { console.log('SUCCESS update data user') },
		error: function() { console.log('ERROR update data user') }
	})
}
