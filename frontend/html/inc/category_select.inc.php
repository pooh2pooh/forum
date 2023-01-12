<label class="h5 mb-3" for="topicCategorySelect">Категория</label>
<select class="form-select mb-4" aria-label="Select category topic" id="topicCategorySelect" name="CATEGORY">

	<?php
		$categories = $this->getAllCategories();
		foreach($categories as $category)
		{
			echo "<option value=$category[id]>$category[name]</option>";
		}
	?>
  
</select>