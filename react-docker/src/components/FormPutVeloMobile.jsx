const FormPutVeloMobile = ({ veloMobile, handleSubmitFormPostVeloMobile, handleSubmitFormPutVeloMobile }) => {
  console.log(`veloMobile dans FormPutVeloMobile`, veloMobile);
  return (
    <>
      <form className="d-flex gap-2 mb-5"
        onSubmit={(event) => {
          handleSubmitFormPutVeloMobile(event, veloMobile.id);
        }}
        action="">
        <div className="d-flex flex-column">
          <label htmlFor="model" className="form-label">Modèle :</label>
          <input type="text" id="model" name="model" defaultValue={veloMobile.model} />
        </div>
        <div className="d-flex flex-column">
          <label htmlFor="description" className="form-label">Description : </label>
          <textarea name="description" id="description" cols="30" rows="10" defaultValue={veloMobile.description}></textarea>
        </div>
        <div className="d-flex flex-column">
          <label htmlFor="weight" className="form-label">Poids :</label>
          <input type="text" name="weight" id="weight" defaultValue={veloMobile.weight} />
        </div>
        <div className="d-flex flex-column">
          <label htmlFor="photo" className="form-label">Photo :</label>
          <input type="text" id="photo" name="photo" defaultValue={veloMobile.photo} />
        </div>
        <button type="submit" className="btn btn-success align-self-start mt-4">Envoyer</button>
      </form>
    </>
  );
}

export default FormPutVeloMobile;