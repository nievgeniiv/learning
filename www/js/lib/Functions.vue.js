/**
 *
 * @param link
 * @param k
 * @param handler
 */
function search(link, k, handler) {
  setTimeout(function () {
    getRequest(link + k, handler);
  }, timer);
}

function getRequest(link, handler) {
  axios.get(link)
    .then((response) => {
      handler(response.data);
    })
    .catch(function (error) {
    });
}

function postReqest(linkPost, linkGet, data, handler) {
  axios({
    method: 'post',
    url: linkPost,
    data: {
      data: data
    }
  })
    .then(() => {
      getRequest(linkGet, handler)
    })
    .catch(function (error) {
    });
}

/**
 * @return {boolean}
 */
function hasDataCheckbox(dataCheckbox) {
  return dataCheckbox.length !== 0;
}

/**
 * @param selectAll
 * @param checkedNames
 * @param dataJSON
 * @returns {(*[]|boolean)[]}
 * @constructor
 */
function CheckboxSelect(selectAll, checkedNames, dataJSON) {
  if (selectAll === false) {
    checkedNames = [];
    if (checkedNames.length === 0) {
      let i = 0;
      while (i <= dataJSON.length - 1) {
        checkedNames.push(dataJSON[i].identificate);
        i++;
      }
    }
    selectAll = true;
  } else {
    checkedNames = [];
    selectAll = false;
  }
  return [checkedNames, selectAll];
}

function getDateNow() {
  let dateNow = new Date();
  return dateNow.getTime();
}

