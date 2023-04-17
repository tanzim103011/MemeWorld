<?php $this->view('header')?>
	
	<div class="p-2 col-md-6 shadow mx-auto border rounded">

		<div class="text-center"> 
			
			<span>
				<img class="profile-image rounded-circle m-4" src="<?=get_image($row->image)?>" style="width: 200px;height: 200px;object-fit: cover;">
				
				<?php if(user('id') == $row->id):?>
					<label>
						<i style="position: absolute;cursor: pointer;" class="h1 text-primary bi bi-image"></i>
						<input onchange="display_image(this.files[0])" type="file" class="d-none" name="">
					</label>
				<?php endif;?>

			</span>
			<div class="profile-image-prog progress d-none">
			  <div class="progress-bar" style="width: 0%;" >0%</div>
			</div>

			<h3><?=esc($row->username)?></h3>
			<script>
				
				function display_image(file)
				{
					let allowed = ['jpg','jpeg','png','webp'];
					let ext = file.name.split(".").pop();

					if(!allowed.includes(ext.toLowerCase()))
					{
						alert('Only files of this type allowed: '+ allowed.toString(", "));
						return;
					}

					document.querySelector(".profile-image").src = URL.createObjectURL(file);
					change_image(file);
				}

				function display_post_image(file)
				{
					let allowed = ['jpg','jpeg','png','webp'];
					let ext = file.name.split(".").pop();

					if(!allowed.includes(ext.toLowerCase()))
					{
						alert('Only files of this type allowed: '+ allowed.toString(", "));
						post_image_added = false;
						return;
					}

					document.querySelector(".post-image").src = URL.createObjectURL(file);
					document.querySelector(".post-image").parentNode.classList.remove("d-none");
					
					post_image_added = true;
				}
				

			</script>
		</div>

		<!--post area-->
		<?php if(user('id') == $row->id):?>
			<div>
				<form method="post" onsubmit="submit_post(event)">
					<div class="bg-secondary p-2">
						<textarea id="post-input" rows="4" class="form-control" placeholder="Whats on your mind?"></textarea>
						
						<label>
							<i style="cursor: pointer;" class="h1 text-white bi bi-image"></i>
							<input id="post-image-input" onchange="display_post_image(this.files[0])" type="file" class="d-none" name="">
						</label>
						<button class="mt-1 btn btn-warning float-end">Post</button>
						
						<div class="text-center d-none">
							<img class="post-image m-1" src="" style="width: 100px;height: 100px;object-fit: cover;">
						</div>

						<div class="clearfix"></div>
					</div>
				</form>
				<div class="post-prog progress d-none">
				  <div class="progress-bar" style="width: 0%;" >0%</div>
				</div>
			</div>
		<?php endif;?>
		<!--end post area-->

		<div class="my-3">
			
			<?php if(!empty($posts)):?>
				<?php foreach($posts as $post):?>
					<?php $this->view('post-small',['post'=>$post])?>
				<?php endforeach;?>
			<?php endif;?>

			<?php $pager->display()?>
		</div>
	</div>

	<script>
		
		var post_image_added = false;

		function change_image(file)
		{
			var obj = {};
			obj.image = file;
			obj.data_type = "profile-image";
			obj.id = "<?=user('id')?>";
			obj.progressbar = 'profile-image-prog';
			send_data(obj);
		}

		function submit_post(e)
		{
			e.preventDefault();

			var obj = {};
			if(post_image_added)
				obj.image = e.currentTarget.querySelector("#post-image-input").files[0];

			obj.post = e.currentTarget.querySelector("#post-input").value;
			obj.data_type = "create-post";
			obj.id = "<?=user('id')?>";
			obj.progressbar = 'post-prog';
			send_data(obj);
		}

		function send_data(obj)
		{

			var myform = new FormData();
			var progressbar = null;

			if(typeof obj.progressbar != 'undefined')
				progressbar = document.querySelector("."+obj.progressbar);

			for(key in obj)
			{
				myform.append(key,obj[key]);
			}

			var ajax = new XMLHttpRequest();

			ajax.addEventListener('readystatechange',function(e){

				if(ajax.readyState == 4 && ajax.status == 200)
				{
					handle_result(ajax.responseText);
				}
			});

			if(progressbar)
			{	
				progressbar.classList.remove("d-none");
				progressbar.children[0].style.width = "0%";
				progressbar.children[0].innerHTML = "0%";

				ajax.upload.addEventListener('progress',function(e){

					let percent = Math.round((e.loaded / e.total) * 100);
					progressbar.children[0].style.width = percent + "%";
					progressbar.children[0].innerHTML = percent + "%";
				});
			}

			ajax.open('post','<?=ROOT?>/ajax',true);
			ajax.send(myform);

		}

		function handle_result(result)
		{
			//console.log(result);
			let obj = JSON.parse(result);

			if(obj.data_type == "profile-image")
			{
				alert(obj.message);
				window.location.reload();
			}else
			if(obj.data_type == "create-post")
			{
				alert(obj.message);
				window.location.reload();
			}
		}

	</script>
<?php $this->view('footer')?>