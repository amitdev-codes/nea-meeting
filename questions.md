1. componenets name and its related sub componenets if sub componenets is dependent or not..
2. 

  logic i have a form in fansep project and its entry module is this.

    1. data entry 

                 conditions
                 1. filled by data entry user and role is according to section if crop then crop specialist if nutriion then nutrition specialist
                 2. and user must be  according to that cluster if form is of gorkha cluster then user must be of gorkha cluster and if user has 
                  given 2 clusters to handle then he can enter 2 clusters data.




 2.  verification level 1
    that form is verified by the user who has been given the access to verify the level 1 can have 1 clustter or multiple cluster by user.
    


  3. verification level 2

  same form is then verified in level 2by some releveant specialist pcu of that cluster may be of same sector may not be.


  4. verification level 3
 same form is then verified in level 3 by some releveant specialist with same sector.here cluster is not entertained.

  5. verification level 4
   same form is then verified in level 4 by some releveant specialist with same sector.here cluster is not entertained.

 6. verification level 5 or final verification

  same form is then verified in level 5 by some senior M&E officer.


           Entry Module
  # form 
  select componenet->select lmbis activity ->select form
  and open form

 2. select fiscal year->select group name if single ormultiple and local_level_id  which all automatically give cluster name and district.

   and then open beneficiary in grid filled.

   # table can be

   fiscal_year_id,component,lmbis_activity,form_id,group_id

Crop Seeds Receiving Beneficiaries
      group_name--dropdown from groups table
     -beneficiary id --dropdown from group_members table
     seed/sapling source --dropdown
     name of crop-  dropdown from crops table
     crop variety --dropdown crop_varieties
     seed/sapling unit --dropdown mst_length_units table
     quantity--number
     remarks text area

