function ConfirmDelete()
{
  var x = confirm("Etes vous surs de vouloir supprimer ?");
  if (x)
      return true;
  else
    return false;
}