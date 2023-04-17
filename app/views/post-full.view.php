<div class="row post p-1">
	<div class="col-3 bg-light text-center">
		<a href="<?=ROOT?>/profile/<?=$post->user->id?>">
			<img class="profile-image rounded-circle m-1" src="<?=get_image($post->user->image ?? '')?>" style="width: 80px;height: 80px;object-fit: cover;">
			<h5><?=esc($post->user->username ?? 'Unknown')?></h5>
		</a>
	</div>
	<div class="col-9 text-start">
		<div class="muted"><?=get_date($post->date)?></div>
		<p><?=esc($post->post)?></p>

		<?php if(!empty($post->image)):?>
			<a href="<?=ROOT?>/post/<?=$post->id?>">
				<img class="my-1" src="<?=get_image($post->image)?>" style="width: 100%;">
			</a>
		<?php endif;?>

		<div>
			<?php if(user('id') == $post->user_id):?>
				<a href="<?=ROOT?>/post/edit/<?=$post->id?>">
					<button class="btn-sm m-1 btn btn-warning">Edit</button>
				</a>
				<a href="<?=ROOT?>/post/delete/<?=$post->id?>">
					<button class="btn-sm m-1 btn btn-danger">Delete</button>
				</a>
			<?php endif;?>

		</div>
	</div>
	<hr>
	<h5>Comments:</h5>
		<!--comment area-->
		
			<div>
				<form method="post" onsubmit="submit_comment(event)">
					<div class="bg-secondary p-2">
						<textarea id="comment-input" rows="4" class="form-control" placeholder="Type a comment here"></textarea>
						<input type="hidden" id="post_id" value="<?=$post->id?>">
						<label>
							<i style="cursor: pointer;" class="h1 text-white bi bi-image"></i>
							<input id="comment-image-input" onchange="display_comment_image(this.files[0])" type="file" class="d-none" name="">
						</label>
						<button class="mt-1 btn btn-warning float-end">comment</button>
						
						<div class="text-center d-none">
							<img class="comment-image m-1" src="" style="width: 100px;height: 100px;object-fit: cover;">
						</div>

						<div class="clearfix"></div>
					</div>
				</form>

				<script>
					
					function display_comment_image(file)
					{
						let allowed = ['jpg','jpeg','png','webp'];
						let ext = file.name.split(".").pop();

						if(!allowed.includes(ext.toLowerCase()))
						{
							alert('Only files of this type allowed: '+ allowed.toString(", "));
							comment_image_added = false;
							return;
						}

						document.querySelector(".comment-image").src = URL.createObjectURL(file);
						document.querySelector(".comment-image").parentNode.classList.remove("d-none");
						
						comment_image_added = true;
					}

				</script>
				<div class="comment-prog progress d-none">
				  <div class="progress-bar" style="width: 0%;" >0%</div>
				</div>
			</div>

		<!--end comment area-->

		<div class="my-3">
			
			<?php if(!empty($comments)):?>
				<?php foreach($comments as $comment):?>
					<?php $this->view('comment-small',['comment'=>$comment])?>
				<?php endforeach;?>
			<?php endif;?>

		</div>

</div>
<br>

	<script>
		
		var comment_image_added = false;

		function submit_comment(e)
		{
			e.preventDefault();

			var obj = {};
			if(comment_image_added)
				obj.image = e.currentTarget.querySelector("#comment-image-input").files[0];

			obj.comment = e.currentTarget.querySelector("#comment-input").value;
			obj.post_id = e.currentTarget.querySelector("#post_id").value;
			obj.data_type = "create-comment";
			obj.id = "<?=user('id')?>";
			obj.progressbar = 'comment-prog';
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

			alert(obj.message);
			window.location.reload();
		}

	</script>
