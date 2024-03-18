const FormPostVeloMobile = ({ handleSubmitFormPostVeloMobile }) => {
  return (
    <form className="d-flex gap-3" id="form-add-velomobile"
      onSubmit={(event) => {
        handleSubmitFormPostVeloMobile(event)
      }}
      action="">
      <div className="d-flex flex-column">
        <label htmlFor="model">Modèle :</label>
        <input type="text" id="model" name="model" />
      </div>
      <div className="d-flex flex-column">
        <label htmlFor="description">Description :</label>
        <textarea name="description" id="description" cols="30" rows="10"></textarea>
      </div>
      <div className="d-flex flex-column">
        <label htmlFor="weight">Poids :</label>
        <input type="text" name="weight" id="weight" />
      </div>
      <div className="d-flex flex-column">
        <label htmlFor="photo">Photo :</label>
        <input type="text" id="photo" name="photo" />
      </div>
      <button type="submit" className="btn btn-success align-self-start mt-4">Envoyer</button>
    </form>
  );
}

export default FormPostVeloMobile;