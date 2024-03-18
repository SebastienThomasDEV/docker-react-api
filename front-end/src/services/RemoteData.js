export default class RemoteData {
  static url = "http://localhost:3001/";
  /**
   * L'effet global de cette méthode est d'envoyer une requête à un serveur distant, de vérifier si la requête a réussi, d'analyser la réponse au format JSON, de l'afficher dans la console  et de la renvoyer. Si une étape échoue, il affiche un message d'erreur.
   * 
   * @returns Promise<{}[]>
   */
  static loadVelosMobiles() {
    return fetch(RemoteData.url + "velosMobiles")
      .then((response) => {
        console.log(`response.status`, response.status);
        if (response.status == 200) {
          return response.json();
        } else throw new Error("Problème de serveur dans loadVelosMobiles. Statut de l'erreur : " + response.status)
      })
      .then((velosMobiles) => {
        console.log(`velosMobiles`, velosMobiles);
        return velosMobiles;
      })
  }
  static loadUsers() {
    return fetch(RemoteData.url + "users")
      .then((response) => {
        console.log(`response.status`, response.status);
        if (response.status == 200) {
          return response.json();
        } else throw new Error("Problème de serveur dans loadUsers. Statut de l'erreur : " + response.status)
      })
      .then((users) => {
        console.log(`users`, users);
        return users;
      })
  }
  static isLogged(login, pwd) {
    console.log(`DAns isLogged`, login, pwd);
    return RemoteData.loadUsers()
      .then((users) => {
        let isLogged = false;
        for (let i = 0; i < users.length; i++) {
          if (login === users[i].login && pwd === users[i].pwd) {
            return true;
          }
        }
        return false;
      })
  }
  /**
   * Supprime un vélomobile en bd via une requête http utilisant le verbe DELETE
   * @param {*} id 
   * @returns Promise<deleted Object veloMobile>
   */
  static deleteVeloMobile(id) {
    return fetch(`${RemoteData.url}velosMobiles/${id}`,
      {
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json'
        },
        method: "DELETE",
      })
      .then((response) => {
        console.log(`response.status`, response.status);
        if (response.status == 200) {
          return response.json();
        } else throw new Error("Problème de serveur dans deleteVeloMobile. Statut de l'erreur : " + response.status)
      })
      .then((veloMobile) => {
        console.log(`veloMobile supprimé : `, veloMobile);
        return veloMobile;
      })
  }
  /**
  * 
  * @param {*} newVeloMobile 
  * @returns 
  */
  static postVeloMobile(newVeloMobile) {
    const copynewVeloMobile = { ...newVeloMobile };
    delete copynewVeloMobile.id;
    return fetch(`${RemoteData.url}velosMobiles/`, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      method: "POST",
      body: JSON.stringify(copynewVeloMobile)
    })
      .then((response) => {
        console.log(`response.status de post VeloMobile`, response.status);
        if (response.status !== 201) throw new Error("Erreur " + response.status)
        return response.json();
      })
      .then(data => {
        console.log(`data reçue après le post : `, data);
        return data;
      })

  }
  /**
  * 
  * @param {*} newVeloMobile 
  * @returns 
  */
  static putVeloMobile(updatedVeloMobile) {
    console.log(`DansputVeloMobile `, updatedVeloMobile);
    return fetch(`${RemoteData.url}velosMobiles/${updatedVeloMobile.id}`, {
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      },
      method: "PUT",
      body: JSON.stringify(updatedVeloMobile)
    })
      .then((response) => {
        console.log(`response.status de put VeloMobile`, response.status);
        if (response.status !== 200) throw new Error("Erreur " + response.status)
        return response.json();
      })
      .then(data => {
        console.log(`data reçue après le put : `, data);
        return data;
      })

  }
}