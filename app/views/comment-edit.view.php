<?php $this->view('header')?>
  
  <div class="row p-2 col-md-8 shadow mx-auto border rounded">

    <div class="my-3">

    	<?php if(!empty($comment)):?>
    	<form method="post" onsubmit="submit_comment(event)">
		<div class="row post p-1">

			<center><h5>Edit Comment</h5></center>

			<div class="row post p-1">
				<div class="col-3 bg-light text-center">
					<a href="<?=ROOT?>/profile/<?=$comment->user->id?>">
						<img class="profile-image rounded-circle m-1" src="<?=get_image($comment->user->image ?? '')?>" style="width: 80px;height: 80px;object-fit: cover;">
						<h5><?=esc($comment->user->username ?? 'Unknown')?></h5>
					</a>
				</div>
				<div class="col-9 text-start">
					<div class="muted"><?=get_date($comment->date)?></div>

					<div>
						<div class="bg-secondary p-2">
							<textarea id="comment-input" rows="6" class="form-control" placeholder="Whats on your mind?"><?=$comment->comment?></textarea>
							
							<label>
								<i style="cursor: pointer;" class="h1 text-white bi bi-image"></i>
								<input id="comment-image-input" onchange="display_comment_image(this.files[0])" type="file" class="d-none" name="">
							</label>
							<button class="mt-1 btn btn-warning float-end">Save</button>
							
							<div class="text-center d-none">
								<img class="comment-image m-1" src="" style="width: 100px;height: 100px;object-fit: cover;">
							</div>

							<div class="clearfix"></div>
						</div>

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
					</div>

					<?php if(!empty($comment->image)):?>
						<a href="<?=ROOT?>/comment/<?=$comment->id?>">
							<img class="my-1" src="<?=get_image($comment->image)?>" style="width: 100%;">
						</a>
					<?php endif;?>
					<input type="hidden" id="comment_id" value="<?=$comment->id?>">
					<div>
						<?php if(user('id') == $comment->user_id):?>
							<a href="<?=ROOT?>/comment/<?=$comment->id?>">
								<button type="button" class="btn-sm m-1 btn btn-secondary">Back</button>
							</a>
							
						<?php endif;?>

					</div>
				</div>

			</div>

		</div>
		</form>
		<?php else:?>
			<div class="m-1 alert alert-danger text-center">Sorry! That record was not found!</div>
		<?php endif;?>

		<div class="comment-prog progress d-none">
		  <div class="progress-bar" style="width: 0%;" >0%</div>
		</div>
	</div>
  </div>

	<script>
		
		var comment_image_added = false;
 
		function submit_comment(e)
		{
			e.preventDefault();

			var obj = {};
			if(comment_image_added)
				obj.image = e.currentTarget.querySelector("#comment-image-input").files[0];

			obj.comment = e.currentTarget.querySelector("#comment-input").value;
			obj.comment_id = e.currentTarget.querySelector("#comment_id").value;
			obj.data_type = "edit-comment";
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
			console.log(result);
			let obj = JSON.parse(result);

			alert(obj.message);

			if(obj.success)
				window.location.href = '<?=ROOT?>/comment/<?=$comment->id ?? 0?>';
		}

	</script>

<?php $this->view('footer')?>