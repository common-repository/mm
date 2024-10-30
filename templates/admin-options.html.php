<!--
  WordPress Mm

  Copyright (c) 2013 David Persson. All rights reserved.

  Use of this source code is governed by a BSD-style
  license that can be found in the license.txt file.
-->
<h2>Mm Settings</h2>

<h3>Status</h3>
<table class="form-table">
	<tbody>
		<?php foreach ($mediaProcessConfig as $name => $adapter): ?>
		<tr>
			<th>Process <em><?php echo $name ?></em> adapter</th>
			<td><?php echo $adapter ?></td>
		</tr>
		<?php endforeach ?>
		<?php foreach ($mimeTypeConfig as $name => $adapter): ?>
		<tr>
			<th>MIME-type <em><?php echo $name ?></em> adapter</th>
			<td><?php echo $adapter ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
<h3>Versions</h3>
<p>
	This allows you to configure existing versions.
</p>
<form action="options-general.php?page=mm" method="post">
<?php foreach ($versions as $version): ?>
	<h4><?php echo $version ?></h4>
	<table class="form-table">
		<tbody>
			<tr>
				<th scope="row">
					<label>Dimensions (width x height)</label>
				</th>
				<td>
				<input type="number" name="versions[<?php echo $version ?>][width]" value="<?php echo $options['versions'][$version]['width']?>" />
					x
					<input type="number" name="versions[<?php echo $version ?>][height]" value="<?php echo $options['versions'][$version]['height']?>" />
					<p class="description">
						Leave width or height empty if there is no constraint on that dimension.
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label>Method</label>
				</th>
				<td>
					<select name="versions[<?php echo $version ?>][method]">
					<?php foreach ($methods as $name => $title): ?>
						<option
							value="<?php echo $name ?>"
							<?php if ($options['versions'][$version]['method'] == $name): ?>
								selected
							<?php endif ?>
						>
							<?php echo $title ?>
						</option>
					<?php endforeach ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label>Format</label>
				</th>
				<td>
					<select name="versions[<?php echo $version ?>][mime_type]">
					<?php foreach ($formats as $name => $title): ?>
						<option
							value="<?php echo $name ?>"
							<?php if ($options['versions'][$version]['mime_type'] == $name): ?>
								selected
							<?php endif ?>
						>
							<?php echo $title ?>
						</option>
					<?php endforeach ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">
					Optimizations
				</th>
				<td>
				<?php foreach ($optimizations as $key => $value): ?>
					<input
						type="checkbox"
						id="version-<?php echo $version ?>-<?php echo $key ?>"
						name="versions[<?php echo $version ?>][<?php echo $key ?>]"
						<?php if (isset($options['versions'][$version][$key])): ?>
							checked
						<?php endif ?>
					/>
					<label for="version-<?php echo $version ?>-<?php echo $key ?>"><?php echo $value ?></label><br/>
				<?php endforeach ?>
				</td>
			</tr>
		</tbody>
	</table>
<?php endforeach ?>
	<input type="submit" name="submit" id="submit" class="button-primary" value="Save Changes">
</form>