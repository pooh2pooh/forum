const editor = new EditorJS({
	holder: 'editorjs',
	placeholder: 'Let`s write  an awesome story!',
	tools: {
		header: Header,
		list: List,
		image: SimpleImage,

	},
	data: {},
});

function SendPost(topic_id) {
	editor.save().then((outputData) => {
		console.log('Article data: ', outputData)
		$.ajax({
			url: 'read_topic.php?topic_id=' + topic_id,
			type: 'POST',
			data: outputData,
			success: function(data) {
				console.log('OK');
			},
			error: function() {
				console.log('ERROR');
			}
		});
	}).catch((error) => {
		console.log('Saving failed: ', error)
	});
}
