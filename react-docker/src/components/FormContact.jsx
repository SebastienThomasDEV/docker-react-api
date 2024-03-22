const FormContact = () => {
  return (
    <form id="form-contact">
      <label htmlFor="lastname">Prénom</label>
      <input type="text" id="lastname" name="lastname" />
      <button type="submit" className="btn btn-success">Envoyer</button>
    </form>
  );
}

export default FormContact;