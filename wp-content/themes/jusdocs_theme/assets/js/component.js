import $ from "jquery";
import { post } from "./wpApi";

export const fetch = async (componentElement, componentName) => {
  const componentEndpoint = "get_component";

  const props = componentElement.data("props");
  const formData = new FormData();

  formData.append("component", componentName);
  formData.append("args", JSON.stringify(props));

  $(componentElement).addClass("updating");

  try {
    const { data } = await post(componentEndpoint, formData);

    //console.log(data);
    return data.component;
  } catch (error) {
    return false;
  } finally {
    $(componentElement).removeClass("updating");
  }
};

export const componentFetch = (componentName, props) => {
  const componentEndpoint = "get_component";

  return $.ajax({
    method: "POST",
    url: hdlComponentsData.url,
    data: {
      action: componentEndpoint,
      nonce: hdlComponentsData.nonce,
      component: componentName,
      args: JSON.stringify(props),
    },
    cache: false,
  });
};

export const componentUpdate = async (
  component,
  componentName,
  customProps = false
) => {
  const props = component.data("props");

  if (customProps) {
    jQuery.extend(props, customProps);
  }

  const data = await componentFetch(componentName, props);

  if (typeof data.component !== "undefined") {
    return $($.parseHTML(data.component.trim()));
  } else {
    return false;
  }
};

export const setComponentIdentifier = (components, slug) => {
  if (components) {
    for (let i = 0; i < components.length; i++) {
      const component = $(components[i]);
      const compIdentifier = `component-${slug}-${i}`;

      component.attr("data-identifier", compIdentifier);
    }
  }
};

export const getComponentIdentifier = (component) => {
  return component.data("identifier");
};

export const onUpdateComponent = (componentName, selector, identifier) => {
  $(document).on("componentUpdate", selector, async function(e, args) {
    const componentToUpdate = $(this);

    componentToUpdate.addClass("updating");

    const newComponent = await componentUpdate(
      componentToUpdate,
      componentName,
      args
    );

    if (newComponent) {
      //console.log(newComponent, "update-success");
      setComponentIdentifier(newComponent, identifier);
      componentToUpdate.replaceWith(newComponent);
      newComponent.trigger("componentUpdateSuccess");
    } else {
      //console.log(newComponent, "update-failed");
      newComponent.trigger("componentUpdateFailure");
    }
  });
};
