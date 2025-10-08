
    <select id="report_type" name="report_type" onChange="passLinkType();" >
                <option value="all" <?php if($typSearch=='all'){ echo "selected";}else{ echo ""; } ?> >ALL</option>
                <option value="hsse" <?php if($typSearch=='hsse'){ echo "selected";}else{ echo ""; } ?> >HSSE</option>
                <option value="sq" <?php if($typSearch=='sq'){ echo "selected";}else{ echo ""; } ?> >SQ</option>
                <option value="jn" <?php if($typSearch=='jn'){ echo "selected";}else{ echo ""; } ?> >Journey</option>
                <option value="audit" <?php if($typSearch=='audit'){ echo "selected";}else{ echo ""; } ?> >Audit / Inspection</option>
                <option value="document" <?php if($typSearch=='document'){ echo "selected";}else{ echo ""; } ?> >Documentation</option>
                <option value="certificate" <?php if($typSearch=='certificate'){echo "selected";}else{ echo ""; } ?> >Certification</option>
                <option value="job" <?php if($typSearch=='job'){echo "selected";}else{ echo ""; } ?> >Job</option>
                <option value="lesson" <?php if($typSearch=='lesson'){echo "selected";}else{ echo ""; } ?> >Best Practice / Lesson Learnt</option>
                <option value="suggestion" <?php if($typSearch=='suggestion'){echo "selected";}else{ echo ""; } ?> >Suggestion</option>
                <option value="jha" <?php if($typSearch=='jha'){echo "selected";}else{ echo ""; } ?> >Job Hazard Analysis</option>
    </select>