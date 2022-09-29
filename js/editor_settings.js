const editor = new EditorJS({
	holder: 'editorjs',
	placeholder: 'Let`s write  an awesome story!',
	tools: {
		header: Header,
		list: List,
		image: SimpleImage,

	},
	// autofocus: true,
});

$('#modalEditor').on('shown.bs.modal', function () {
  // get the locator for an input in your modal. Here I'm focusing on
  // the element with the id of <editor>
  editor.focus()
})

function SendPost(topic_id) {
	editor.save().then((outputData) => {
		console.log('Article data: ', outputData)
		$.ajax({
			url: 'read_topic.php?topic_id=' + topic_id,
			type: 'POST',
			data: outputData,
			success: function(data) {
				console.log('SUCCESS send post');
				location.reload();
			},
			error: function() {
				console.log('ERROR send post');
			}
		});
	}).catch((error) => {
		console.log('Saving failed: ', error)
	});
}

function UpdateTopic(topic_id) {

	// Загружаем новую обложку топика
	var formData = new FormData;
	formData.append('topic_cover', $("#topicCoverInput").prop('files')[0]);

	$.ajax({
		url: 'read_topic.php?topic_id=' + topic_id,
		type: 'POST',
		data: formData,
		processData: false,
        contentType: false,
		success: function(data) {
			console.log('SUCCESS update cover topic');
		},
		error: function() {
			console.log('ERROR update cover topic');
		}
	});

	// Обновляем данные топика
	$.ajax({
		url: 'read_topic.php?topic_id=' + topic_id,
		type: 'POST',
		data: {
			topic_name: $('#topicNameInput').val(),
			topic_first_post: $('#topicFirstPostInput').val()
		},
		success: function(data) {
			console.log('SUCCESS update data topic');
		},
		error: function() {
			console.log('ERROR update data topic');
		}
	});

	location.reload();
}