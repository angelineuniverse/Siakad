import React, { Component, ReactNode, lazy } from "react";
import { ModelForm, FormProps } from "./model";
import clsx from "clsx";
import Skeleton from "../../components/skeleton/skeleton";

const Input = lazy(() => import("../../components/input/input"));
const Select = lazy(() => import("../../components/select/select"));

class Form extends Component<ModelForm> {
  render(): ReactNode {
    return (
      <div className={clsx(this.props.className)}>
        {!this.props.form && (
          <div
            className={clsx(
              this.props.classNameLoading ?? "grid grid-cols-1 gap-x-2 gap-y-4"
            )}
          >
            <Skeleton type="input" />
            <Skeleton type="input" />
            <Skeleton type="input" />
          </div>
        )}
        {this.props.form?.map((item: FormProps | any) => (
          <div key={item.key}>
            {(() => {
              switch (item.type) {
                case "password":
                case "text":
                  return (
                    <Input
                      isRequired={item.isRequired}
                      placeholder={item.placeholder}
                      key={item.key}
                      description={item.description}
                      readonly={item.readonly ?? false}
                      defaultValue={item[item.key ?? ""]}
                      label={item.label}
                      type={item.type}
                      onValueChange={(value: any) => (item[item.key!] = value)}
                    />
                  );
                case "select":
                  return (
                    <Select
                      isRequired={item.isRequired}
                      key={item.key}
                      placeholder={item.placeholder}
                      label={item.label}
                      onClick={(event: any) => {
                        item[item.key!] = event.target.value;
                      }}
                      keyValue={item.list?.keyValue}
                      keyoption={item.list?.keyoption}
                      options={item.list?.options}
                    />
                  );
                default:
                  return null;
              }
            })()}
          </div>
        ))}
      </div>
    );
  }
}

export default Form;
