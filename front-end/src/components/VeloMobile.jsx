import { useOutletContext } from "react-router-dom";
import FormPutVeloMobile from "./FormPutVeloMobile";
const VeloMobile = (props) => {
  const [isLoggedIn, setIsLoggedIn] = useOutletContext();
  return (
    <section className="section-velomobile d-flex justify-content-between gap-3">
      <h3 className="h2">{props.veloMobile.model}</h3>
      {isLoggedIn &&
        (
          <>


            {props.upVeloMobile && <FormPutVeloMobile veloMobile={props.veloMobile} handleSubmitFormPutVeloMobile={props.handleSubmitFormPutVeloMobile} />}
            {!props.upVeloMobile && (
              <>
                <img className="mb-5" src={`/images/velosmobiles/${props.veloMobile.photo}`} alt="" />
                <p className="mb-5">{props.veloMobile.description}</p>
                <section className="admin-bar w-25">
                  <button
                    onClick={() => {
                      props.handleClickDeleteVeloMobile(props.veloMobile);
                    }}
                    className="btn btn-danger mx-2">Supprimer</button>
                  <button
                    onClick={() => {
                      props.handleClickUpVeloMobile(props.veloMobile);
                    }}
                    className="btn btn-warning">Modifier</button>
                </section>
              </>)}
          </>
        )}
      {!isLoggedIn && (<>
        <img src={`/images/velosmobiles/${props.veloMobile.photo}`} alt="" className="mb-5" />
        <p className="mb-5">{props.veloMobile.description}</p>
      </>)
      }

    </section >
  );
}

export default VeloMobile;