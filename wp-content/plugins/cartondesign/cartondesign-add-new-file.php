<style>

    input {
        padding: 4px 7px;
    }

    .cfile {
        margin: 2px;
        border: 3px solid #eeeeee;
        background: #fafafa;
        overflow: hidden;
        padding: 5px;
        margin-bottom: 10px;
    }

    .dfile {
        margin: 2px;
        border: 1px solid #800;
        background: #ffdfdf;
        overflow: hidden;
        padding: 5px;
    }

    .cfile img, .dfile img {
        cursor: pointer;
    }

    .inside {
        padding: 10px !important;
    }

    #editorcontainer textarea {
        border: 0px;
        width: 99.9%;
    }

    #file_uploadUploader {
        background: transparent url('<?php echo plugins_url('/download-manager/images/browse.png'); ?>') left top no-repeat;
    }

    #file_uploadUploader:hover {
        background-position: left bottom;
    }

    .frm td {
        line-height: 30px;
        border-bottom: 1px solid #EEEEEE;
        padding: 5px;
        font-size: 9pt;
        font-family: Tahoma;
    }

    .form_item {
        padding: 5px;
        margin-bottom: 10px;
    }

    .form_item > label {
        font-weight: 600;
    }

    .error{
        color:red;
    }
</style>

<div class="wrap metabox-holder has-right-sidebar">

    <div class="icon32" id="icon-add-new-file"><br></div>
    <h2>Add New Carton Design Layout</h2>

    <form id="wpdmpack" action="" method="post" enctype="multipart/form-data">

        <div style="width: 75%;float:left;">
            <?php
            $carton_types = get_carton_types();
            ?>


            <?php if(isset($errorMsg) && !empty($errorMsg)){
                echo "<ul>";
                foreach($errorMsg as $errorItem)
                {?>
                 <li><?php echo "<p class='error'>$errorItem</p>" ?></li>
                <?php } echo "</ul>"; } ?>

            <div class="form_item">
                <label>Carton Type</label><br>
                <select name="type_id">
                    <option <?php if (!isset($cartonDesignLayout['type_id'])) echo "selected=selected" ?>>Select Type
                    </option>
                    <?php
                    foreach ($carton_types as $carton_Row) {
                        ?>
                        <option
                            value="<?php echo $carton_Row->id ?>" <?php if (isset($cartonDesignLayout['type_id']) && $cartonDesignLayout['type_id'] == $carton_Row->id) echo "selected=selected" ?>><?php echo $carton_Row->name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form_item">
                <label>
                    Design Image
                </label><br>
                <input type="file" name="design_image">
            </div>

            <div class="form_item">
                <label>
                    Product Code
                </label><br>
                <input type="text" name="product_code" <?php if(isset($cartonDesignLayout['product_code'])) echo 'value=\''.$cartonDesignLayout['product_code'].'\'';?> placeholder="Please enter product code">
            </div>

            <div class="form_item">
                <label>
                    Design Length
                </label><br>
                <input type="text" name="design_length" <?php if(isset($cartonDesignLayout['design_length'])) echo 'value=\''.$cartonDesignLayout['design_length'].'\'';?> placeholder="Please enter design length">
            </div>

            <div class="form_item">
                <label>
                    Design Width
                </label><br>
                <input type="text" name="design_width" <?php if(isset($cartonDesignLayout['design_width'])) echo 'value=\''.$cartonDesignLayout['design_width'].'\'';?> placeholder="Please enter design width">
            </div>

            <div class="form_item">
                <label>
                    Design Depth
                </label><br>
                <input type="text" name="design_depth" <?php if(isset($cartonDesignLayout['design_depth'])) echo 'value=\''.$cartonDesignLayout['design_depth'].'\'';?> placeholder="Please enter design depth ">
            </div>

            <div class="form_item">
                <label>
                    Design Layout
                </label><br>
                <input type="file" name="design_layout">
            </div>
            <input type="hidden" name="action" value="save_carton_design"/>
            <div class="form_item">
                <input type="submit" value="Add Carton Design">
            </div>
        </div>
    </form>
</div>

       
